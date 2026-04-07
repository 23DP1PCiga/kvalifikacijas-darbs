<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { Chart, BarController, BarElement, CategoryScale, LinearScale } from 'chart.js'

Chart.register(BarController, BarElement, CategoryScale, LinearScale)

const router = useRouter()
const books = ref([])
const snackbar = ref(false)
const snackbarText = ref('')


const load = async () => {
  const res = await axios.get('/api/books')
  books.value = res.data
}

const stats = ref(null)

const loadStats = async () => {
  
  const res = await axios.get('/api/admin/stats')
  stats.value = res.data
}

const goToBook = (id) => {
  router.push('/books/' + id)
}

const deleteBook = async (id) => {
  if (!confirm('Vai tiešām dzēst grāmatu?')) return

  await axios.delete('/api/admin/books/' + id)

  books.value = books.value.filter(b => b.id !== id)

  snackbarText.value = 'Grāmata dzēsta'
  snackbar.value = true
}

onMounted(() => {
  load()
  loadStats()
})
</script>

<template>
  <v-container>
    <h1>Admin panel</h1>
    
    <v-btn to="/admin/create" variant="tonal" color="accent">
      + Pievienot grāmatu
    </v-btn>

    <v-row class="mt-4">
      <v-col v-for="b in books" :key="b.id" cols="3">

        <v-card
          class="book-card"
          @click="goToBook(b.id)"
        >
          <v-img
            :src="b.cover && b.cover.startsWith('books/')
              ? '/storage/' + b.cover
              : b.cover"
            height="200"
            cover
          />

          <v-card-title>{{ b.title }}</v-card-title>

          <v-card-actions>
            <v-btn @click.stop.prevent="$router.push('/admin/edit/' + b.id)">
              Rediģēt
            </v-btn>

            <v-btn color="accent" @click.stop.prevent="deleteBook(b.id)">
              Dzēst
            </v-btn>
          </v-card-actions>

        </v-card>

      </v-col>
    </v-row>

    <v-snackbar v-model="snackbar">
      {{ snackbarText }}
    </v-snackbar>

  </v-container>
</template>

<style>
.book-card {
  cursor: pointer;
  transition: 0.2s;
}

.book-card:hover {
  transform: translateY(-4px);
}

.v-card {
  border-radius: 16px;
  font-family: "ABeeZee", sans-serif;
}

h2{
   color: #424242
}
</style>