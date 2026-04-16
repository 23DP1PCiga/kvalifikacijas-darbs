<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

axios.defaults.withCredentials = true
axios.defaults.baseURL = 'http://127.0.0.1:8001'

const route = useRoute()

const book = ref(null)
const rating = ref(0)
const newComment = ref('')
const comments = ref([])
const user = ref(null) 

const readBook = () => {
  if (!book.value?.file) return
  window.open('/storage/' + book.value.file, '_blank')
}

const deleteReview = async (id) => {
  if (user.value?.role === 'admin') {
    await axios.delete('/api/admin/reviews/' + id)
  } else {
    await axios.delete('/api/reviews/' + id)
  }

  comments.value = comments.value.filter(c => c.id !== id)
}

const loadUser = async () => {
  try {
    const res = await axios.get('/user')
    user.value = res.data
  } catch {
    user.value = null
  }
}

const loadBook = async () => {
  const response = await axios.get('/api/books/' + route.params.id)
  book.value = response.data
}

const loadComments = async () => {
  try {
    const res = await axios.get(`/api/books/${route.params.id}/comments`)
    comments.value = Array.isArray(res.data) ? res.data : []
  } catch (e) {
    console.error(e)
    comments.value = []
  }
}

const addComment = async () => {
  if (!newComment.value.trim() || rating.value === 0) return

  await axios.get('/sanctum/csrf-cookie')

  await axios.post('/api/comments', {
    book_id: route.params.id,
    comment: newComment.value,
    rating: rating.value
  })

  newComment.value = ''
  rating.value = 0

  await loadComments()
  await loadBook()
}

const saveBook = async () => {
  await axios.get('/sanctum/csrf-cookie')

  await axios.post('/api/saved-books', {
    book_id: route.params.id
  })
}

onMounted(() => {
  loadUser()    
  loadBook()
  loadComments()
})
</script>

<template>
<div v-if="book" class="book-page">
<div class="top-section">

<v-img
  :src="book.cover && book.cover.startsWith('books/')
    ? '/storage/' + book.cover
    : book.cover"
    width="250"
    height="360"
/>

    <div class="info">

      <div class="author">
        {{ book.author }}
      </div>

      <div class="title">
        {{ book.title }}
      </div>

      <div class="actions">
        <v-btn variant="tonal" color="accent" @click="readBook">Lasīt</v-btn>
        <v-btn @click="saveBook" variant="tonal" color="accent">Saglabāt</v-btn>
      </div>

      <div class="description">
        {{ book.description }}
      </div>

     <div class="rating">
    <v-rating
      :model-value="book.reviews_avg_rating || 0"
      readonly
      half-increments
      color="#F59E0B"
    />

  <span class="rating-value">
  {{ book.reviews_avg_rating ? Number(book.reviews_avg_rating).toFixed(1) : '0.0' }}
</span>

    <span class="rating-count">
      ({{ book.reviews_count || 0 }})
    </span>
  </div>

    </div>
  </div>

  <div class="comments-section">

  <div class="review-title">Dalieties savā viedoklī</div>

  <div v-if="user">

    <v-rating
      v-model="rating"
      length="5"
      size="32"
      color="#F59E0B"
    />

    <v-textarea
      v-model="newComment"
      placeholder="Pastāstiet citiem, ko jūs domājat par šo grāmatu"
      variant="outlined"
    />

    <v-btn 
      variant="tonal" 
      color="accent" 
      @click="addComment"
      :disabled="!newComment.trim()"
    >
      Iesniegt
    </v-btn>

  </div>

  <div v-else class="mt-4 text-grey">
    Lūdzu, piesakieties lai komentētu
  </div>

  <div v-if="comments.filter(c => c.comment).length === 0" class="mt-4" >
    Nav komentāru
  </div>

<div 
  v-for="comment in comments.filter(c => c.comment)" 
  :key="comment.id"
  class="comment"
>
  <div class="comment-date">
    {{ new Date(comment.created_at).toLocaleDateString('lv-LV') }}
  </div>

  <div class="comment-left">
    <div class="user-name">
      {{ comment.user?.user_name || 'User' }}
    </div>

    <v-rating
      :model-value="comment.rating || 0"
      readonly
      size="18"
      density="compact"
      color="#F59E0B"
    />

    <div class="comment-text">
      {{ comment.comment }}
    </div>
  </div>

  <div class="comment-actions">
    <v-btn
      v-if="user?.role === 'admin' || user?.id === comment.user_id"
      class="delete-btn"
      @click.stop="deleteReview(comment.id)"
    >
      <v-icon size="18">mdi-delete</v-icon>
    </v-btn>
  </div>

</div>
</div>

</div>
</template>

<style>
.top-section{
  display:flex;
  gap:80px;
  margin-top:40px;
  align-items:flex-start;
  max-width:900px;
  margin-left: 50px;
}

.title{
  font-size:40px;
  font-weight:600;
  color:#424242;
  font-family:"ABeeZee", sans-serif;
   margin-bottom:10px;
}

.author{
  font-size:18px;
  color:#424242;
  margin-bottom:10px;
  font-family:"ABeeZee", sans-serif;
}

.description{
  max-width:700px;
  margin-top:20px;
  font-family:"ABeeZee", sans-serif;
}

.comments-section{
  margin-top:60px;
  max-width:700px;
  margin-left: 50px;
}

.review-title{
  font-size:30px;
  font-weight:400;
  color:#424242;
  font-family:"ABeeZee", sans-serif;
  margin-bottom:15px;
}

.actions {
  display: flex;
  gap: 12px; 
  margin-top: 10px;
}

.comment-actions {
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
}

.comment {
  position: relative; 
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-top: 20px;
  border-bottom: 1px solid #eee;
  gap: 20px;
  padding-top: 10px;
}

.comment-date {
  position: absolute;
  top: 0;
  right: 0;
  font-size: 12px;
  color: #999;
  font-family: "ABeeZee", sans-serif;
}

.comment-left {
  flex: 1;
  word-break: break-word;
  overflow-wrap: break-word;
  min-width: 0;
}

.user-name {
  font-weight: 600;
  margin-bottom: 5px;
  color: #424242;
}

.delete-btn {
  width: 40px !important;
  height: 40px !important;
  min-width: 40px !important;
  border-radius: 50% !important;
  flex-shrink: 0;
  background-color: white !important; 
  display: flex;
  align-items: center;
  justify-content: center;

  padding: 0 !important;
}


</style>