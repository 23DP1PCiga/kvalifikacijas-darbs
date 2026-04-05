<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const books = ref([])

const loadSavedBooks = async () => {
  try {
    const res = await axios.get('/api/saved-books')
    books.value = res.data
  } catch (e) {
    console.error(e)
    books.value = []
  }
}

onMounted(loadSavedBooks)

const remove = async (id) => {
  await axios.delete('/api/saved-books/' + id)
  books.value = books.value.filter(b => b.id !== id)
}
</script>

<template>
  <v-container>
    <h1 class="title">Saglabātās grāmatas</h1>

    <div v-if="books.length === 0">
      Nav saglabātu grāmatu
    </div>

    <v-row v-else>
      <v-col
        v-for="book in books"
        :key="book.id"
        cols="12"
        sm="6"
        md="3"
      >
        <v-card
        :to="`/books/${book.id}`"
        class="book-card"
        >
        <v-img :src="book.cover" height="200" />

        <v-card-title>
            {{ book.title }}
        </v-card-title>

        <v-card-subtitle>
            {{ book.author }}
        </v-card-subtitle>

        <v-card-actions>
            <v-btn color="accent"@click.stop.prevent="remove(book.id)">Noņemt</v-btn>
        </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<style>
.title {
  font-family: "ABeeZee", sans-serif;
  margin-bottom: 20px;
}
</style>