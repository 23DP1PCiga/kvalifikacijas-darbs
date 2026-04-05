<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const ratings = ref([])

const load = async () => {
  const res = await axios.get('/api/my-ratings')
  ratings.value = res.data
}

onMounted(load)
</script>

<template>
  <v-container>
    <h1>Mani vērtējumi</h1>

    <div v-if="ratings.length === 0">
      Nav vērtējumu
    </div>

    <v-card
      v-for="r in ratings"
      :key="r.id"
      class="mb-4 pa-3"
    >
      <div class="book-title">
        {{ r.book.title }}
      </div>

      <v-rating
        :model-value="r.rating"
        readonly
        color="#F59E0B"
      />
    </v-card>
  </v-container>
</template>