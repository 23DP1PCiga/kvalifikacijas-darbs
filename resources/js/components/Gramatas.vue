<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const books = ref([])
const sortType = ref('all')

const loadBooks = async () => {
  const response = await axios.get('api/books')
  books.value = response.data
}

onMounted(loadBooks)

const sortedBooks = () => {
  let result = [...books.value]

  if (sortType.value === 'rating') {
    result.sort((a,b)=> b.ratings_avg_rating - a.ratings_avg_rating)
  }

  if (sortType.value === 'az') {
    result.sort((a,b)=> a.title.localeCompare(b.title))
  }

  if (sortType.value === 'new') {
    result.sort((a,b)=> new Date(b.created_at) - new Date(a.created_at))
  }

  return result
}
</script>

<template>

<v-container>

<!-- CHIPS -->
<div class="d-flex ga-4 mb-6">

<v-chip
@click="sortType='all'"
:color="sortType==='all' ? 'primary' : ''"
>All</v-chip>

<v-chip
@click="sortType='rating'"
:color="sortType==='rating' ? 'primary' : ''"
>Top rated</v-chip>

<v-chip
@click="sortType='new'"
:color="sortType==='new' ? 'primary' : ''"
>Newest</v-chip>

<v-chip
@click="sortType='az'"
:color="sortType==='az' ? 'primary' : ''"
>A-Z</v-chip>
</div>

<v-row>
<v-col
v-for="book in sortedBooks()"
:key="book.id"
cols="auto"
>
<div class="book-card">

<v-img
:src="book.cover"
width="190"
height="260"
cover
/>

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
          />
          <span class="rating-text">
            {{ book.ratings_avg_rating?.toFixed(1) || 0 }}
          </span>
        </div>


</div>

</v-col>

</v-row>

</v-container>

</template>

<style>
.book-card {
  width: 200px;
}

.book-image {
  border-radius: 4px;
  box-shadow: 0 6px 14px rgba(0,0,0,0.08);
}

.book-type {
  margin-top: 12px;
  font-size: 14px;
  color: #6b7280;
}

.book-title {
  font-size: 20px;
  font-weight: 500;
  margin-top: 6px;
  line-height: 1.2;
  font-family: "ABeeZee", sans-serif;
  font-style: normal;
}

.book-author {
  margin-top: 6px;
  font-size: 16px;
  font-family: "ABeeZee", sans-serif;
  font-style: normal;
}

.rating-text {
  font-size: 14px;
  color: #6b7280;
}

.section-title{
  font-family: "ABeeZee", sans-serif;
  font-size: 35px;
  font-weight: 600;
  margin-top: 60px;
  margin-bottom: 40px;
  margin-left: 20px;
  color: #828270;
}
</style>
