<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'
import Chart from 'chart.js/auto'

const stats = ref(null)

const ratingsCanvas = ref(null)
let ratingsChart = null

const loadRatingsChart = async () => {
  const res = await axios.get('/api/admin/ratings')

  await nextTick()

  if (ratingsChart) ratingsChart.destroy()

  ratingsChart = new Chart(ratingsCanvas.value, {
    type: 'bar',
    data: {
      labels: res.data.labels.map(r => r ),
      datasets: [
        {
          label: 'Skaits',
          data: res.data.data
        }
      ]
    }
  })
}

const chartCanvas = ref(null)
let chartInstance = null

const loadStats = async () => {
  const res = await axios.get('/api/admin/stats')
  stats.value = res.data
}

const loadChart = async () => {
  const res = await axios.get('/api/admin/chart')

  await nextTick()

  if (chartInstance) chartInstance.destroy()

  chartInstance = new Chart(chartCanvas.value, {
    type: 'bar',
    data: {
      labels: res.data.labels,
      datasets: [
        {
          label: 'Atsauksmes',
          data: res.data.data
        }
      ]
    }
  })
}

onMounted(() => {
  const user = JSON.parse(localStorage.getItem('user'))

  if (!user || user.role !== 'admin') {
    window.location = '/'
  }

  loadStats()
  loadChart()
  loadRatingsChart()
})
</script>

<template>
  <v-container>
    <h1>Statistika</h1>
    <v-row v-if="stats" class="mb-6">

      <v-col cols="12" md="3">
        <v-card class="pa-4 text-center">
          {{ stats.books }}<br>
          Grāmatas
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card class="pa-4 text-center">
          {{ stats.users }}<br>
          Lietotāji
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card class="pa-4 text-center">
          {{ stats.reviews }}<br>
          Atsauksmes
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card class="pa-4 text-center">
          {{ stats.avg_rating || 0 }}<br>
          Vidējais vērtējums
        </v-card>
      </v-col>

    </v-row>

    <v-card class="pa-4">
    <h3>Vislielākais atsauksmju skaits</h3>
    <canvas ref="chartCanvas"></canvas>
    </v-card>
    
    <v-card class="pa-4 mt-6">
    <h3>Vērtējumu sadalījums</h3>
    <canvas ref="ratingsCanvas"></canvas>
    </v-card>
    
  </v-container>
</template>

<style>
h1 {
  font-family: "ABeeZee", sans-serif;
  margin-bottom: 20px;
  color: #424242;
}
</style>