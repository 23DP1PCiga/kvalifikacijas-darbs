<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const boards = ref([])
const dialog = ref(false)

const editDialog = ref(false)
const editForm = ref({
  id: null,
  title: '',
  description: ''
})

const load = async () => {
  await axios.get('/sanctum/csrf-cookie')
  const res = await axios.get('/api/boards')
  boards.value = res.data
}

import { useRouter } from 'vue-router'
const router = useRouter()
const openBoard = (id) => {
  router.push('/boards/' + id)
}

const editBoard = (board) => {
  editForm.value = { ...board }
  editDialog.value = true
}

const updateBoard = async () => {
  await axios.put('/api/boards/' + editForm.value.id, {
    title: editForm.value.title,
    description: editForm.value.description
  })

  const index = boards.value.findIndex(b => b.id === editForm.value.id)
  boards.value[index] = { ...editForm.value }

  editDialog.value = false
}

const deleteBoard = async (id) => {
  if (!confirm('Dzēst kolekciju?')) return

  await axios.delete('/api/boards/' + id)

  boards.value = boards.value.filter(b => b.id !== id)
}

onMounted(load)
</script>

<template>
<v-container>
  <h1 class="title">Kolekcijas</h1>
  <div v-if="boards.length === 0">
    Nav kolekciju
  </div>
  <v-row v-else>
    <v-col
      v-for="board in boards"
      :key="board.id"
      cols="12"
      sm="6"
      md="3"
    >
    <v-card class="board-card" @click="openBoard(board.id)">

    <div class="board-content">
    <div class="board-title">{{ board.title }}</div>

    <div class="board-desc">{{ board.description }}</div>
    </div>

  <div class="board-actions">
    <v-btn variant="tonal" size="small" @click.stop="editBoard(board)">
      Rediģēt
    </v-btn>

    <v-btn variant="tonal" size="small" color="accent" @click.stop="deleteBoard(board.id)">
      Dzēst
    </v-btn>
  </div>

</v-card>
    </v-col>
  </v-row>

  <v-dialog v-model="editDialog" max-width="400">
  <v-card>
    <v-card-title>Rediģēt kolekciju</v-card-title>

    <v-card-text>
      <v-text-field v-model="editForm.title" label="Nosaukums" />
      <v-textarea v-model="editForm.description" label="Apraksts" />
    </v-card-text>
    <v-card-actions>
      <v-btn color="accent" @click="updateBoard">
        Saglabāt
      </v-btn>
    </v-card-actions>
  </v-card>
</v-dialog>
</v-container>
</template>

<style>

h1{
    padding-bottom: 20px;
    padding-top: 20px;
}
.board-title {
  font-size: 24px;
  font-weight: 600;
  color: #424242;
  font-family: "ABeeZee", sans-serif;
}

.board-card {
  height: 220px;
  aspect-ratio: 1 / 1;
  border-radius: 20px;
  padding: 40px;
  position: relative;
  align-items: center;
  cursor: pointer;
  transition: 0.2s;
  background: white;
  box-shadow: 0 10px 30px rgba(130, 130, 112, 0.35);
 
}

.board-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.board-content {
  text-align: center;
}

.board-desc {
  margin-top: 10px;
  justify-content: center;
  align-items: center;
  font-size: 14px;
  color: #555;
  max-height: 60px;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 3; /* максимум 3 строки */
  -webkit-box-orient: vertical;
  word-break: break-word;
}

.board-actions {
  position: absolute;
  bottom: 12px;
  left: 0;
  right: 0;
  display: flex;
  justify-content: center;
  gap: 10px;
}
</style>