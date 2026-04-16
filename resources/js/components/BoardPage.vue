<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const board = ref(null)

const load = async () => {
  const res = await axios.get('/api/boards/' + route.params.id)
  board.value = res.data
}

const removeBook = async (id) => {
  await axios.delete('/api/boards/' + board.value.id + '/books/' + id)

  board.value.books = board.value.books.filter(b => b.id !== id)
}

onMounted(load)
</script>

<template>
<v-container v-if="board">
  <h1 class="title">{{ board.title }}</h1>
  <div class="description">
    {{ board.description }}
  </div>

  <div v-if="board.books.length === 0">
    Nav grāmatu
  </div>
  <v-row v-else class="mt-4">

    <v-col
      v-for="book in board.books"
      :key="book.id"
      cols="12"
      sm="6"
      md="3"
    >
      <v-card class="book-card">
        <div @click="router.push('/books/' + book.id)">
          <v-img
            :src="book.cover && book.cover.startsWith('books/')
              ? '/storage/' + book.cover
              : book.cover"
            height="200"
          />
          <v-card-title>{{ book.title }}</v-card-title>
          <v-card-subtitle>{{ book.author }}</v-card-subtitle>
        </div>
        <v-card-actions>
          <v-btn color="accent" @click.stop="removeBook(book.id)">
            Noņemt
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</v-container>
</template>

<style>
.title {
  font-family: "ABeeZee", sans-serif;
  margin-bottom: 0px;
  color: #424242;
}

.description {
  color: #666;
  margin-bottom: 60px;
}

.book-card {
  cursor: pointer;
  transition: 0.2s;
}

.book-card:hover {
  transform: translateY(-4px);
}
</style>