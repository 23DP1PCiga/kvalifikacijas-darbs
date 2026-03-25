<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'

const route = useRoute()
const book = ref(null)
const rating = ref(0)
const newComment = ref('')
const comments = ref([])



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
  if (!newComment.value.trim()) return

  await axios.post('/api/comments', {
    book_id: route.params.id,
    comment: newComment.value,
    rating: rating.value
  })

  newComment.value = ''
  rating.value = 0

  await loadComments() 
}

onMounted(() => {
  loadBook()
  loadComments()
})
</script>

<template>
<div v-if="book" class="book-page">

  <div class="top-section">

  <v-img
    :src="book.cover"
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
      <v-btn variant="tonal" color="accent">Lasīt</v-btn>
    </div>

    <div class="description">
      {{ book.description }}
    </div>

    <div class="rating">
      <v-rating
        :model-value="book.ratings_avg_rating || 0"
        readonly
        half-increments
      />
      {{ book.ratings_avg_rating?.toFixed(1) || 0 }}
    </div>

  </div>
 </div>
</div>

  
  <div class="comments-section">

    <div class="review-title">Dalieties savā viedoklī</div>

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

    <v-btn variant="tonal" color="accent" @click="addComment" 
    :disabled="!newComment.trim()">
      Iesniegt
    </v-btn>
    
    <div v-if="comments.filter(c => c.comment).length === 0" class="mt-4">
      Nav komentāru
    </div>

    <div 
    v-for="comment in (Array.isArray(comments) ? comments : []).filter(c => c.comment)" 
    :key="comment.id"
    class="comment"
    >
      ⭐ {{ comment.rating || 0 }}
      <div>{{ comment.comment }}</div>
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

.comment{
  margin-top:20px;
  padding:15px 0;
  border-bottom:1px solid #eee;
}
</style>