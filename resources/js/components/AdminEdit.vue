<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const form = ref({})

const load = async () => {
  const res = await axios.get('/api/books/' + route.params.id)
  form.value = res.data
}

const update = async () => {
  await axios.put('/api/admin/books/' + route.params.id, form.value)
  router.push('/admin')
}

onMounted(load)
</script>

<template>
  <v-container>
    <h1>Rediģēt grāmatu</h1>

    <v-text-field v-model="form.title" label="Nosaukums" />
    <v-text-field v-model="form.genre" label="Žanrs" />
    <v-text-field v-model="form.price" label="Cena" />
    <v-text-field v-model="form.cover" label="Cover URL" />
    <v-text-field v-model="form.publishing_year" label="Gads" />

    <v-textarea v-model="form.description" label="Apraksts" />

    <v-btn color="primary" @click="update">
      Saglabāt
    </v-btn>
  </v-container>
</template>