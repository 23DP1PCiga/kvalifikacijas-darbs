<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const books = ref([])

const dialog = ref(false)
const boards = ref([])
const selectedBoard = ref(null)
const newBoardTitle = ref('')
const newBoardDesc = ref('')
const selectedBookId = ref(null)

const loadBoards = async () => {
  const res = await axios.get('/api/boards')
  boards.value = res.data
}

const openDialog = async (bookId) => {
  selectedBookId.value = bookId
  await loadBoards()
  dialog.value = true
}

const addToBoard = async () => {
  if (!selectedBoard.value) return

  await axios.post('/api/boards/add', {
    board_id: selectedBoard.value,
    book_id: selectedBookId.value
  })
  dialog.value = false
}

const createBoardAndAdd = async () => {
  const res = await axios.post('/api/boards', {
    title: newBoardTitle.value,
    description: newBoardDesc.value
  })

  await axios.post('/api/boards/add', {
    board_id: res.data.id,
    book_id: selectedBookId.value
  })

  dialog.value = false
  newBoardTitle.value = ''
  newBoardDesc.value = ''
}

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
        <v-card class="book-card">
        <div @click="$router.push(`/books/${book.id}`)">

        <v-img
        :src="book.cover && book.cover.startsWith('books/')
          ? '/storage/' + book.cover
          : book.cover"
        height="200"/>

        <v-card-title>{{ book.title }}</v-card-title>

        <v-card-subtitle>{{ book.author }}</v-card-subtitle>
        </div>
        <v-card-actions>
            <v-btn color="accent"@click.stop.prevent="remove(book.id)">Noņemt</v-btn>
        </v-card-actions>

        <v-btn variant="tonal" color="primary" @click="openDialog(book.id)">
          Pievienot kolekcijai
        </v-btn>
        </v-card>
      </v-col>
    </v-row>

    <v-dialog v-model="dialog" max-width="500">
   <v-card>
   <v-card-title>Pievienot kolekcijai</v-card-title>

    <v-card-text>
      <v-select
        v-model="selectedBoard"
        :items="boards"
        item-title="title"
        item-value="id"
        label="Izvēlies kolekciju"
      />

      <v-btn class="mt-2" color="accent" variant="tonal" @click="addToBoard">
        Pievienot
      </v-btn>
      <v-divider class="my-4"></v-divider>

      <v-text-field v-model="newBoardTitle" label="Jauna kolekcija"/>
      <v-textarea v-model="newBoardDesc" label="Apraksts"/>

      <v-btn class="mt-2" color="accent" @click="createBoardAndAdd">
        Izveidot un pievienot
      </v-btn>
    </v-card-text>
  </v-card>
</v-dialog>
</v-container>
</template>

<style>
.title {
  font-family: "ABeeZee", sans-serif;
  margin-bottom: 40px;
  color: #424242;
}

.v-card {
  cursor: pointer;
  transition: 0.2s;
}

.v-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}
</style>