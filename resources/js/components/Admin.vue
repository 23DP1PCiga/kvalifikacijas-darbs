<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const books = ref([])

const load = async () => {
  const res = await axios.get('/api/books')
  books.value = res.data
}

const deleteBook = async (id) => {
  await axios.delete('/api/admin/books/' + id)
  books.value = books.value.filter(b => b.id !== id)
}

onMounted(load)
</script>

<template>
  <v-container>
    <h1>Admin panel</h1>

    <v-btn to="/admin/create" variant="tonal" color="accent">
      + Pievienot grāmatu
    </v-btn>

    <v-row class="mt-4">
      <v-col v-for="b in books" :key="b.id" cols="3">

        <v-card>
          <v-img :src="b.cover" height="200" />

          <v-card-title>{{ b.title }}</v-card-title>

          <v-card-actions>
            <v-btn @click="$router.push('/admin/edit/' + b.id)">Rediģēt</v-btn>

            <v-btn color="accent" @click="deleteBook(b.id)">Dzēst</v-btn>
          </v-card-actions>

        </v-card>

      </v-col>
    </v-row>
  </v-container>
</template>