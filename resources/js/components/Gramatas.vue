<script setup>
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'
import { ref, onMounted, watch } from 'vue'

const books = ref([])
const sortType = ref('all')
const sortOption = ref('new')
const route = useRoute()
const router = useRouter()

const handleSort = (item) => {

  if(item.value === 'all'){
    sortType.value = 'all'
    router.push('/books')   
  } 
  else {
    sortOption.value = item.value
  }

}

const items = [
  { title: 'Visi', value: 'all' },
  { title: 'Jaunākās', value: 'new' },
  { title: 'No A-Z', value: 'az' },
  { title: 'Augstākais vērtējums', value: 'rating' }
]

const loadBooks = async () => {
  if(route.query.q){
    const response = await axios.get('/books/search?q=' + route.query.q)
    books.value = response.data

  } 
  else {

    const response = await axios.get('/api/books')
    books.value = response.data

  }

}


onMounted(loadBooks)
watch(() => route.query.q, () => {
  loadBooks()
})

const sortedBooks = () => {
  let result = [...books.value]

  if (sortType.value !== 'all') {
    result = result.filter(book => book.genre === sortType.value)
  }

   if (sortOption.value === 'az') {
  result.sort((a,b)=> a.title.localeCompare(b.title))
 }

 if (sortOption.value === 'rating') {
  result.sort((a,b)=> b.rating - a.rating)
 }

 if (sortOption.value === 'new') {
  result.sort((a,b)=> b.publishing_year - a.publishing_year)
 }

  return result
}
</script>

<template>
<v-container>
<div class="d-flex align-center justify-space-between mb-6">
<div class="d-flex ga-4 mb-6">

<v-chip
@click="sortType='Trilleri'"
:color="sortType==='Trilleri' ? 'accent' : ''"
>Trilleri</v-chip>

<v-chip
@click="sortType='Fantāzijas'"
:color="sortType==='Fantāzijas' ? 'accent' : ''"
>Fantāzijas</v-chip>

<v-chip
@click="sortType='Klasika'"
:color="sortType==='Klasika' ? 'accent' : ''"
>Klasika</v-chip>

<v-chip
@click="sortType='Šausmas'"
:color="sortType==='Šausmas' ? 'accent' : ''"
>Šausmas</v-chip>

<v-chip
@click="sortType='Bizness'"
:color="sortType==='Bizness' ? 'accent' : ''"
>Bizness</v-chip>

<v-chip
@click="sortType='Finanses'"
:color="sortType==='Finanses' ? 'accent' : ''"
>Finanses</v-chip>
</div>

<v-menu>
  <template v-slot:activator="{ props }">
     <v-btn class="sortet" v-bind="props" variant="tonal" color="accent">Sortēt</v-btn>
  </template>
  <v-list>
    <v-list-item
      v-for="(item, index) in items"
      :key="index"
      @click="handleSort(item)"
    >
      <v-list-item-title>
        {{ item.title }}
      </v-list-item-title>
    </v-list-item>
  </v-list>
</v-menu>

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

.sortet{
    font-family: "ABeeZee", sans-serif;
    font-style: normal;
    position:relative;
    right:93px;
}
</style>
