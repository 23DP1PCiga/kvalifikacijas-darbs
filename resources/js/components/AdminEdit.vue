<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const form = ref({})
const file = ref(null)

const handleFile = (fileInput) => {
  file.value = fileInput
}

const load = async () => {
  const res = await axios.get('/api/books/' + route.params.id)
  form.value = res.data
}

const update = async () => {
  await axios.get('/sanctum/csrf-cookie')

  const formData = new FormData()

  formData.append('title', form.value.title)
  formData.append('author', form.value.author)
  formData.append('genre', form.value.genre)
  formData.append('price', form.value.price)
  formData.append('publishing_year', form.value.publishing_year)
  formData.append('description', form.value.description)

  if (file.value) {
    formData.append('cover', file.value)
  }

  await axios.post(
    '/api/admin/books/' + route.params.id + '?_method=PUT',
    formData,
    {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    }
  )

  router.push('/admin')
}

onMounted(load)
</script>

<template>
  <v-container>
    <h1>Rediģēt grāmatu</h1>

    <v-text-field v-model="form.title" label="Nosaukums" />
    <v-text-field v-model="form.author" label="Autors" />
    <v-text-field v-model="form.genre" label="Žanrs" />
    <v-text-field v-model="form.price" label="Cena" />
    <v-file-input label="Cover" accept="image/*" @update:modelValue="handleFile"/>
    <v-text-field v-model="form.publishing_year" label="Gads" />

    <v-textarea v-model="form.description" label="Apraksts" />

    <v-btn color="primary" @click="update">
      Saglabāt
    </v-btn>
  </v-container>
</template>