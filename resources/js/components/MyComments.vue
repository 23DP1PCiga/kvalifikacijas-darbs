<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const reviews = ref([])

const goToBook = (id) => {
  router.push('/books/' + id)
}

const load = async () => {
  const res = await axios.get('/api/my-comments')
  reviews.value = res.data
}

onMounted(load)
</script>

<template>
  <v-container>
    <h1>Manas atsauksmes</h1>

    <div v-if="reviews.length === 0">
      Nav atsauksmju
    </div>

      <v-card
        v-for="r in reviews"
        :key="r.id"
        class="mb-4 pa-3 review-card"
        @click="goToBook(r.book.id)"
      >
      <div class="book-title">
        {{ r.book.title }}
      </div>

      <v-rating
        v-if="r.rating"
        :model-value="r.rating"
        readonly
        size="18"
        color="#F59E0B"
      />

      <div v-if="r.comment">
        {{ r.comment }}
      </div>

    </v-card>
  </v-container>
</template>

<style>
h1{
   font-family: "ABeeZee", sans-serif;
  margin-bottom: 20px;
  color: #424242;
}

.review-card {
  cursor: pointer;
  transition: 0.2s;
}

.review-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}
</style>
