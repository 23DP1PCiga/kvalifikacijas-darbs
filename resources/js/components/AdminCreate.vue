<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
  title: '',
  author: '',
  genre: '',
  description: '',
  price: '',
  publishing_year: ''
})

const file = ref(null) 

const handleFile = (fileInput) => {
  file.value = fileInput
}

const create = async () => {
  const formData = new FormData()

  formData.append('title', form.value.title)
  formData.append('author', form.value.author)
  formData.append('genre', form.value.genre || '')
  formData.append('description', form.value.description || '')
  formData.append('price', form.value.price || '')
  formData.append('publishing_year', form.value.publishing_year || '')

  if (file.value) {
    formData.append('cover', file.value)
  }

  try {
    await axios.get('/sanctum/csrf-cookie')
    await axios.post('/api/admin/books', formData)
    router.push('/admin')
  } catch (e) {
    console.log(e.response.data)
  }
}
</script>

<template>
  <v-container>
    <h1>Pievienot grāmatu</h1>

    <v-text-field v-model="form.title" label="Nosaukums" />
    <v-text-field label="Autors"v-model="form.author"/>
    <v-text-field v-model="form.genre" label="Žanrs" />
    <v-text-field v-model="form.price" label="Cena" />
    <v-file-input
  label="Cover"
  accept="image/*"
  @update:modelValue="handleFile"
/>
    <v-text-field v-model="form.publishing_year" label="Gads" />

    <v-textarea v-model="form.description" label="Apraksts" />

    <v-btn color="primary" @click="create">
      Saglabāt
    </v-btn>
  </v-container>
</template>