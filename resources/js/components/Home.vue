```vue
<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const girl = window.location.origin + '/image/hero.png'
const topBooks = ref([])
const newBooks = ref([])

// Atver konkrētās grāmatas lapu
const goToBook = (id) => {
  router.push('/books/' + id)
}

// Ielādē grāmatas no API un sakārto tās pēc vērtējuma un pievienošanas datuma
const load = async () => {
  const res = await axios.get('/api/books')

  // Atlasa piecas grāmatas ar augstāko vidējo vērtējumu
  topBooks.value = [...res.data]
    .sort((a, b) => (b.reviews_avg_rating || 0) - (a.reviews_avg_rating || 0))
    .slice(0, 5)

  // Atlasa piecas jaunākās pievienotās grāmatas
  newBooks.value = [...res.data]
    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
    .slice(0, 5)
}

// Ielādē grāmatas, kad tiek atvērta lapa
onMounted(load)
</script>

<template>
<div class="page">

  <!-- Sākuma lapas galvenā sadaļa -->
  <div class="hero">
    <div class="hero-left">
      <h1>Atrodi savu<br>nākamo mīļāko<br>grāmatu.</h1>
      <p>Atklāj jaunus stāstus. Saglabā tos, kas paliek ar tevi.</p>

      <div class="hero-actions">
        <v-btn to="/books" class="hero-btn-fill" variant="flat">
          Skatīt grāmatas
        </v-btn>
        <v-btn to="/register" class="hero-btn-outline" variant="outlined">
          Izveidot kontu
        </v-btn>
      </div>
    </div>

    <!-- Attēls galvenajā sadaļā -->
    <div class="hero-right">
      <img :src="girl" class="hero-img">
    </div>
  </div>

  <!-- Populārāko grāmatu sadaļa -->
  <h2 class="section-title">Populārākās grāmatas</h2>
  <v-row justify="space-between" class="books-row">
    <v-col v-for="book in topBooks" :key="book.id" cols="auto">
      <!-- Noklikšķinot uz grāmatas, atver tās apraksta lapu -->
      <div class="book-card" @click="goToBook(book.id)">
        <v-img
          :src="book.cover && book.cover.startsWith('books/')
            ? '/storage/' + book.cover
            : book.cover"
          width="210"
          height="310"
          cover
        />

        <div class="book-title">
          {{ book.title }}
        </div>

        <div class="book-author">
          {{ book.author }}
        </div>

        <!-- Parāda grāmatas vidējo vērtējumu -->
        <div class="book-rating">
          <v-rating
            :model-value="Number(book.reviews_avg_rating || 0)"
            half-increments
            readonly
            density="compact"
            size="22"
            color="#f59e0b"
            active-color="#f59e0b"
          />
          <span>{{ Number(book.reviews_avg_rating || 0).toFixed(1) }}</span>
        </div>
      </div>
    </v-col>
  </v-row>

  <!-- Galvenās vietnes iespējas -->
  <v-row class="features">
    <v-col cols="12" md="4">
      <v-card class="feature-card feature-beige" flat>
        <div class="icon-wrapper">
          <v-icon size="36">mdi-book-open-page-variant-outline</v-icon>
        </div>
        <div class="title">Liels grāmatu klāsts</div>
        <div class="feature-text">
          Tūkstošiem grāmatu dažādos žanros
        </div>
      </v-card>
    </v-col>

    <v-col cols="12" md="4">
      <v-card class="feature-card feature-lavender" flat>
        <div class="icon-wrapper">
          <v-icon size="36">mdi-star-outline</v-icon>
        </div>
        <div class="title">Vērtējumi un atsauksmes</div>
        <div class="feature-text">
          Dalies ar savu viedokli un lasi citu atsauksmes
        </div>
      </v-card>
    </v-col>

    <v-col cols="12" md="4">
      <v-card class="feature-card feature-cream" flat>
        <div class="icon-wrapper">
          <v-icon size="36">mdi-bookmark-outline</v-icon>
        </div>
        <div class="title">Saglabātās grāmatas</div>
        <div class="feature-text">
          Veido savas kolekcijas un saglabā favorītus
        </div>
      </v-card>
    </v-col>
  </v-row>

  <!-- Jaunāko grāmatu sadaļa -->
  <h2 class="section-title">Jaunākās grāmatas</h2>

  <v-row justify="space-between" class="books-row">
    <v-col v-for="book in newBooks" :key="book.id" cols="auto">
      <div class="book-card" @click="goToBook(book.id)">
        <v-img
          :src="book.cover && book.cover.startsWith('books/')
            ? '/storage/' + book.cover
            : book.cover"
          width="210"
          height="310"
          cover
        />

        <div class="book-title">
          {{ book.title }}
        </div>

        <div class="book-author">
          {{ book.author }}
        </div>

        <!-- Parāda grāmatas vērtējumu -->
        <div class="book-rating">
          <v-rating
            :model-value="Number(book.reviews_avg_rating || 0)"
            half-increments
            readonly
            density="compact"
            size="22"
            color="#f59e0b"
            active-color="#f59e0b"
          />
          <span>{{ Number(book.reviews_avg_rating || 0).toFixed(1) }}</span>
        </div>
      </div>
    </v-col>
  </v-row>

  <!-- Aicinājums lietotājam izveidot kontu -->
  <div class="cta">
    <div class="cta-content">
      <h2>Sāc lasīt jau šodien</h2>
      <p>Atklāj savu nākamo mīļāko grāmatu</p>

      <v-btn
        to="/register"
        size="large"
        class="cta-btn"
        variant="flat"
      >
        Izveidot kontu
      </v-btn>
    </div>
  </div>

</div>
</template>

<style scoped>
/* Galvenās lapas kopējais platums un atstarpes */
.page {
  max-width: 1300px;
  margin: 0 auto;
  padding: 0 24px 60px;
}

/* Galvenā sadaļa  */
.hero {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-height: 460px;
  padding: 0 50px;
  margin-top: 8px;
  border-radius: 24px;
  overflow: hidden;
  background: rgb(var(--v-theme-beige));
}

.hero-left h1 {
  font-family: "Poppins", sans-serif;
  font-weight: 600;
  font-style: normal;
  font-size: 50px;
  line-height: 1.05;
  white-space: nowrap;
  color: rgb(var(--v-theme-brand));
  margin: 0 !important;
  padding: 0 !important;
  min-height: 0 !important;
}

.hero-left p {
  max-width: 400px;
  font-size: 14px;
  font-family: "Alan Sans", sans-serif;
  color: rgb(var(--v-theme-brand));
  margin: 10px 0 0 !important;
  padding: 0 !important;
}

.hero-actions {
  display: flex;
  gap: 14px;
  margin-top: 28px;
}

.hero-btn-fill {
  background: rgb(var(--v-theme-brand)) !important;
  color: #fff !important;
  border-radius: 8px;
  height: 44px;
  padding: 0 18px;
  text-transform: none;
  letter-spacing: 0;
  font-family: "ABeeZee", sans-serif;
}

.hero-btn-outline {
  border: 1.5px solid rgb(var(--v-theme-brand)) !important;
  color: rgb(var(--v-theme-brand)) !important;
  border-radius: 8px;
  height: 44px;
  padding: 0 24px;
  text-transform: none;
  letter-spacing: 0;
  font-family: "ABeeZee", sans-serif;
}

/* Attēla novietojums galvenajā sadaļā */
.hero-right {
  flex: 1;
  display: flex;
  justify-content: flex-end;
  align-self: flex-end;
}

.hero-img {
  max-height: 420px;
  width: auto;
}

/* Sadaļu virsraksti */
.section-title {
  margin: 70px 0 24px 8px;
  font-family: "Poppins", sans-serif;
  font-weight: 500;
  font-size: 28px;
  color: #2b2b2b;
  text-align: left;
}

/* Grāmatu saraksta izkārtojums */
.books-row {
  padding: 0 4px;
}

/* Grāmatas kartīte */
.book-card {
  width: 210px;
  cursor: pointer;
  transition: 0.2s;
}

.book-card:hover {
  transform: translateY(-5px);
}

.book-title {
  font-size: 22px;
  font-weight: 400;
  margin-top: 10px;
  line-height: 1.2;
  font-family: "ABeeZee", sans-serif;
  font-style: normal;
}

.book-author {
  margin-top: 8px;
  font-size: 17px;
  color: #333;
  font-family: "ABeeZee", sans-serif;
}

/* Grāmatas vērtējuma izkārtojums */
.book-rating {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 6px;
  font-size: 18px;
  font-family: "ABeeZee", sans-serif;
}

.book-rating :deep(.v-rating) {
  margin-left: -4px;
}

.features {
  margin-top: 60px;
}

/* Iespēju kartīšu kopējais stils */
.feature-card {
  border-radius: 20px;
  padding: 40px 42px;
  text-align: left;
  width: 100%;
  min-height: 220px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  transition: 0.3s;
}

.feature-beige { background-color: rgb(var(--v-theme-beige)) !important; }
.feature-lavender { background-color: rgb(var(--v-theme-lavender)) !important; }
.feature-cream { background-color: rgb(var(--v-theme-cream)) !important; }

.feature-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 40px rgba(61, 53, 137, 0.15);
}

/* Ikonas novietojums */
.icon-wrapper {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  margin-bottom: 10px;
  color: rgb(var(--v-theme-brand));
}

.title {
  font-size: 22px;
  font-weight: 500;
  color: #2b2b2b;
  font-family: "ABeeZee", sans-serif;
}

.feature-text {
  margin-top: 8px;
  font-size: 16px;
  color: #444;
  font-family: "ABeeZee", sans-serif;
}

/* Lapas apakšējā sadaļa ar aicinājumu reģistrēties */
.cta {
  margin-top: 70px;
  padding: 70px 20px 80px;
  border-radius: 24px;
  background: rgb(var(--v-theme-brand));
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.cta h2 {
  font-size: 48px;
  font-weight: 500;
  font-family: "Poppins", "ABeeZee", sans-serif;
  color: #ffffff;
}

.cta p {
  margin-top: 10px;
  color: #ffffff;
  font-size: 20px;
  font-family: "ABeeZee", sans-serif;
}

.cta-btn {
  margin-top: 30px;
  border-radius: 8px;
  height: 48px;
  padding: 0 28px;
  font-weight: 700;
  letter-spacing: 0;
  text-transform: none;
  background: rgb(var(--v-theme-beige)) !important;
  color: rgb(var(--v-theme-brand)) !important;
  transition: 0.3s;
}

.cta-btn:hover {
  transform: scale(1.05);
}

/* Pielāgo lapas izkārtojumu mazākiem ekrāniem */
@media (max-width: 960px) {
  .hero { flex-direction: column; padding: 40px 24px 0; }
  .hero-left h1 { font-size: 38px; }
  .cta h2 { font-size: 32px; }
}
</style>
```