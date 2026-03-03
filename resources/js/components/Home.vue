<script setup>
const girl = window.location.origin + '/image/girl.png'

import { ref, onMounted } from 'vue'
import axios from 'axios'

const books = ref([])

onMounted(async () => {
  const response = await axios.get('/books')
  books.value = response.data
})
</script>


<template>
  <div class="hero">
    <div class="circle circle1"></div>
    <div class="circle circle2"></div>
    <div class="circle circle3"></div>
    <div class="circle circle4"></div>
    <div class="hero-left">
      <h1>WELCOME TO READALOT</h1>
      <p>A house of books</p>
    </div>
    <div class="hero-right">
      <img :src="girl" class="hero-img">
    </div>
  </div>

<v-container class="pa-0">
  <v-row class="ga-10">

    <v-col
      v-for="book in books"
      :key="book.id"
      cols="auto"
    >
      <div class="book-card">

        <v-img
          :src="book.cover"
          width="190"
          height="260"
          cover
          class="book-image"
        ></v-img>

        <div class="book-type">
         <!---->
        </div>

        <div class="book-title">
          {{ book.title }}
        </div>

        <div class="book-author">
          {{ book.author }}
        </div>

        <div class="d-flex align-center ga-2 mt-2">
          <v-rating
            :model-value="book.ratings_avg_rating || 0"
            :length="5"
            size="18"
            color="#F59E0B"
            readonly
            half-increments
          ></v-rating>

          <span class="rating-text">
            {{ book.ratings_avg_rating?.toFixed(1) || 0 }}
          </span>
        </div>

      </div>
    </v-col>

  </v-row>
</v-container>
</template>


<style scoped>
.hero {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-height: 600px;
  padding: 0 80px;
  overflow: hidden;
}

.circle {
  position: absolute;
  background: #828270;
  border-radius: 50%;
  opacity: 0.08;
  z-index: 0;
}

.circle1 {
  width: 600px;
  height: 600px;
  top: 200px;
  left: -200px;
}

.circle2 {
  width: 270px;
  height: 270px;
  top: 80px;
  right: -30px;
}

.circle3 {
  width: 200px;
  height: 200px;
  top: 10px;
  left: 400px;
}

.circle4 {
  width: 120px;
  height: 120px;
  top: 400px;
  left: 600px;
}

.hero-right {
  flex: 1;
  display: flex;
  justify-content: flex-end;
   min-width: 500px;
}

.hero-img {
  max-height: 550px;
  width: auto;
}

.hero-left h1 {
  white-space: nowrap;
  font-family: "Archivo Black", sans-serif;
  font-weight: 700;
  font-style:normal;
  font-size: 42px;
  color: #424242;
}

.hero-left p{
  font-size: 30px;
  font-family: "ABeeZee", sans-serif;
  font-weight: 700;
  font-style: normal;
  color: #424242;
}

</style>
