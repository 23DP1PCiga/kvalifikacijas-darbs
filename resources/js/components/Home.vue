<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const girl = window.location.origin + '/image/girl.png'

const topBooks = ref([])
const newBooks = ref([])

const goToBook = (id) => {
  router.push('/books/' + id)
}

const load = async () => {
  const res = await axios.get('/api/books')

  topBooks.value = [...res.data]
    .sort((a,b)=> (b.reviews_avg_rating||0)-(a.reviews_avg_rating||0))
    .slice(0,5)

  newBooks.value = [...res.data]
    .sort((a,b)=> new Date(b.created_at)-new Date(a.created_at))
    .slice(0,5)
}

onMounted(load)
</script>


<template>
  <div class="hero">
    <div class="circle circle1"></div>
    <div class="circle circle2"></div>
    <div class="circle circle3"></div>
    <div class="circle circle4"></div>
    <div class="hero-left">
      <h1>LAIPNI LŪGTI READALOT</h1>
      <p>Daudzu grāmatu māja</p>
    </div>
    <div class="hero-right">
      <img :src="girl" class="hero-img">
    </div>
  </div>

<h2 class="section-title">Populārākās grāmatas</h2>
<v-row justify="center">
  <v-col v-for="book in topBooks" :key="book.id" cols="auto">
    <div class="book-card" @click="goToBook(book.id)">
      <v-img
      :src="book.cover && book.cover.startsWith('books/')
        ? '/storage/' + book.cover
        : book.cover"
        width="210"
        height="280"
    />

    <div class="book-title">
    {{ book.title }}
    </div>

    <div class="book-author">
    {{ book.author }}
    </div>
    </div>
  </v-col>
</v-row>

<v-row class="mt-16 justify-center ga-6">
  <v-col cols="auto">
    <v-card class="feature-card">
      <div class="icon-wrapper">
        <v-icon size="40">mdi-book-open-variant</v-icon>
      </div>
      <div class="title">Liels grāmatu klāsts</div>
      <div class="text">
        Tūkstošiem grāmatu dažādos žanros
      </div>
    </v-card>
  </v-col>

  <v-col cols="auto">
    <v-card class="feature-card">
      <div class="icon-wrapper">
        <v-icon size="40">mdi-star-outline</v-icon>
      </div>
      <div class="title">Vērtējumi un atsauksmes</div>
      <div class="text">
        Dalies ar savu viedokli un lasi citu atsauksmes
      </div>
    </v-card>
  </v-col>

  <v-col cols="auto">
    <v-card class="feature-card">
      <div class="icon-wrapper">
        <v-icon size="40">mdi-bookmark-outline</v-icon>
      </div>
      <div class="title">Saglabā grāmatas</div>
      <div class="text">
        Veido savas kolekcijas un saglabā favorītus
      </div>
    </v-card>
  </v-col>
</v-row>

<h2 class="section-title">Jaunākās grāmatas</h2>

<v-row justify="center">
  <v-col v-for="book in newBooks" :key="book.id" cols="auto">
    <div class="book-card" @click="goToBook(book.id)">
      <v-img
      :src="book.cover && book.cover.startsWith('books/')
        ? '/storage/' + book.cover
        : book.cover"
        width="210"
        height="280"
    />

    <div class="book-title">
    {{ book.title }}
    </div>

    <div class="book-author">
    {{ book.author }}
    </div>
    </div>
  </v-col>
</v-row>


<div class="cta">
  <div class="cta-content">
    <h2>Sāc lasīt jau šodien</h2>
    <p>Atklāj savu nākamo mīļāko grāmatu</p>

    <v-btn 
      to="/books" 
      size="large"
      class="cta-btn"
    >
      Skatīt grāmatas
    </v-btn>
  </div>
</div>

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
  font-size: 40px;
  font-family: "ABeeZee", sans-serif;
  font-weight: 700;
  font-style: normal;
  color: #424242;
  padding-top: 0;
}

.book-card {
  width: 200px;
  cursor:pointer;
  transition:0.2s;
}

.book-card:hover{
  transform:translateY(-5px);
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

.section-title{
   color: #424242;
   text-align: center;
}

.feature-card {
  border-radius: 20px;
  padding: 25px 15px;
  text-align: center;
  transition: 0.3s;
  width: 100%;
  max-width: 300px;
  height: 400px;            
  display: flex;
  flex-direction: column;
  justify-content: center;
  background-color: #f2f2f2;
}

.feature-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 25px 60px rgba(66, 66, 66, 0.4);
}

.icon-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  color: #424242;
}

.title {
  font-size: 25px;
  font-weight: 600;
  color: #424242;
  font-family: "ABeeZee", sans-serif;
  min-height: 48px;
}

.feature-text {
  margin-top: 8px;
  font-size: 18px;
  color: #777;
}

.cta {
  margin-top: 80px;
  padding: 60px 20px;
  border-radius: 30px;

  background: linear-gradient(135deg, #f2f2f2, #e0e0e0);
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.cta h2 {
  font-size: 36px;
  font-weight: 600;
  font-family: "ABeeZee", sans-serif;
  color: #424242;
}

.cta p {
  margin-top: 10px;
  color: #424242;
  font-size: 16px;
  font-family: "ABeeZee", sans-serif;
}

.cta-btn {
  margin-top: 20px;
  border-radius: 10px;
  padding: 12px 30px;
  font-weight: 600;
  letter-spacing: 1px;

  background: #424242 !important; 
  color: white !important;

  transition: 0.3s;
}

.cta-btn:hover {
  transform: scale(1.05);
  box-shadow: 0 10px 30px rgba(255, 255, 255, 0.4);
}

</style>