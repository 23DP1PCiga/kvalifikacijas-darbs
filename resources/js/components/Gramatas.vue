<script setup>
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, onMounted, watch } from 'vue'

const route = useRoute()
const router = useRouter()

const books = ref([])
const loading = ref(true)

// Meklēšanas lauka teksts (sinhronizēts ar adreses parametru ?q=).
const searchText = ref(route.query.q || '')

// Aktīvais žanrs (var nākt arī no saites /books?genre=Klasika).
const sortType = ref(route.query.genre || 'all')
const sortOption = ref('new')

// Pieejamie žanri filtrēšanai.
const genres = ['Trilleri', 'Fantāzijas', 'Klasika', 'Šausmas', 'Bizness', 'Finanses']

// Kārtošanas varianti.
const sortItems = [
  { title: 'Jaunākās', value: 'new' },
  { title: 'No A-Z', value: 'az' },
  { title: 'No Z-A', value: 'za' },
  { title: 'Augstākais vērtējums', value: 'rating' }
]

// Atver izvēlētās grāmatas lapu.
const goToBook = (id) => {
  router.push(`/books/${id}`)
}

// Ja vāks saglabāts Laravel storage mapē, izveido tā publisko adresi.
const coverSrc = (book) => {
  if (!book.cover) {
    return null
  }

  return book.cover.startsWith('books/')
    ? `/storage/${book.cover}`
    : book.cover
}

// Ielādē visas grāmatas vai meklēšanas rezultātus.
const loadBooks = async () => {
  loading.value = true

  try {
    if (route.query.q) {
      const response = await axios.get('/api/books/search', {
        params: { q: route.query.q }
      })
      books.value = response.data
    } else {
      const response = await axios.get('/api/books')
      books.value = response.data
    }
  } catch (error) {
    console.error('Neizdevās ielādēt grāmatas:', error)
    books.value = []
  } finally {
    loading.value = false
  }
}

onMounted(loadBooks)

// Mainoties meklēšanas vaicājumam, atjauno lauku un ielādē rezultātus.
watch(() => route.query.q, (q) => {
  searchText.value = q || ''
  loadBooks()
})

watch(() => route.query.genre, (genre) => {
  sortType.value = genre || 'all'
})

// Atlasa pēc žanra un sakārto izvēlētajā secībā.
const sortedBooks = computed(() => {
  let result = [...books.value]

  if (sortType.value !== 'all') {
    result = result.filter(book => book.genre === sortType.value)
  }

  if (sortOption.value === 'az') {
    result.sort((a, b) => a.title.localeCompare(b.title))
  }

  if (sortOption.value === 'za') {
    result.sort((a, b) => b.title.localeCompare(a.title))
  }

  if (sortOption.value === 'rating') {
    result.sort((a, b) => Number(b.reviews_avg_rating || 0) - Number(a.reviews_avg_rating || 0))
  }

  if (sortOption.value === 'new') {
    result.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
  }

  return result
})

// Meklē grāmatas pēc nosaukuma, autora vai tēmas (žanra filtrs saglabājas).
const searchBooks = () => {
  const q = searchText.value.trim()
  const query = { ...route.query }

  if (q) {
    query.q = q
  } else {
    delete query.q
  }

  router.push({ path: '/books', query })
}

// Notīra meklēšanas vaicājumu.
const clearSearch = () => {
  searchText.value = ''
  const query = { ...route.query }
  delete query.q
  router.push({ path: '/books', query })
}
</script>

<template>
<div class="cat">
  <div class="cat-wrap">

    

    <!-- Meklēšana -->
    <div class="cat-search">
      <div class="cat-search-box">
        <v-icon size="18">mdi-magnify</v-icon>
        <input
          v-model="searchText"
          type="text"
          placeholder="Nosaukums, autors vai tēma..."
          @keyup.enter="searchBooks"
        />
        <button
          v-if="searchText"
          class="cat-search-x"
          title="Notīrīt"
          @click="clearSearch"
        >×</button>
      </div>
      <button class="cat-search-btn" @click="searchBooks">Meklēt</button>
    </div>

    <!-- Žanru filtri un kārtošana -->
    <div class="cat-toolbar">
      <div class="cat-chips">
        <button
          class="cat-chip"
          :class="{ on: sortType === 'all' }"
          @click="sortType = 'all'"
        >Visi</button>

        <button
          v-for="g in genres"
          :key="g"
          class="cat-chip"
          :class="{ on: sortType === g }"
          @click="sortType = g"
        >{{ g }}</button>
      </div>

      <label class="cat-sort">
        <span>Kārtot</span>
        <select v-model="sortOption">
          <option v-for="item in sortItems" :key="item.value" :value="item.value">
            {{ item.title }}
          </option>
        </select>
      </label>
    </div>

    <!-- Grāmatu režģis -->
    <div v-if="sortedBooks.length" class="cat-grid">
      <div
        v-for="book in sortedBooks"
        :key="book.id"
        class="cat-book"
        @click="goToBook(book.id)"
      >
        <img v-if="coverSrc(book)" :src="coverSrc(book)" class="cat-img" />
        <div v-else class="cat-fallback">{{ book.title }}</div>

        <div class="cat-book-title">{{ book.title }}</div>
        <div class="cat-book-author">{{ book.author }}</div>
        <div class="cat-book-rating">
          <span class="cat-star">★</span>
          {{ book.reviews_avg_rating ? Number(book.reviews_avg_rating).toFixed(1) : '0.0' }}
        </div>
      </div>
    </div>

    <div v-else-if="!loading" class="cat-empty">
      Nekas netika atrasts. Pamēģini citu žanru vai meklēšanas vārdu.
    </div>

  </div>
</div>
</template>

<style scoped>
.cat {
  font-family: 'ABeeZee', sans-serif;
  padding: 48px 0 24px;
}

.cat-wrap {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 32px;
}

/* Virsraksts */
.cat-eyebrow {
  font-size: 10px;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  color: rgb(var(--v-theme-moss));
  margin-bottom: 14px;
}

.cat-clear {
  font: inherit;
  font-size: 12px;
  color: rgb(var(--v-theme-ink));
  text-decoration: underline;
  text-decoration-color: rgb(var(--v-theme-mint));
  text-underline-offset: 4px;
  background: none;
  border: 0;
  cursor: pointer;
}

/* Meklēšana */
.cat-search {
  display: flex;
  gap: 8px;
  max-width: 560px;
  margin-top: -10px;
}

.cat-search-box {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 10px;
  height: 44px;
  padding: 0 14px;
  background: #fff;
  border: 1px solid rgb(var(--v-theme-mist));
  border-radius: 4px;
  color: rgb(var(--v-theme-plum));
}

.cat-search-box:focus-within {
  border-color: rgb(var(--v-theme-moss));
}

.cat-search-box input {
  flex: 1;
  min-width: 0;
  border: 0;
  outline: 0;
  background: transparent;
  font: inherit;
  font-size: 14px;
  color: rgb(var(--v-theme-ink));
}

.cat-search-box input::placeholder {
  color: rgb(var(--v-theme-steel));
}

.cat-search-x {
  font-size: 20px;
  line-height: 1;
  color: rgb(var(--v-theme-plum));
  background: none;
  border: 0;
  cursor: pointer;
}

.cat-search-x:hover {
  color: rgb(var(--v-theme-ink));
}

.cat-search-btn {
  height: 44px;
  padding: 0 24px;
  font: inherit;
  font-size: 13px;
  color: #fff;
  background: rgb(var(--v-theme-ink));
  border: 0;
  border-radius: 4px;
  cursor: pointer;
  transition: 0.2s;
}

.cat-search-btn:hover {
  background: rgb(var(--v-theme-graphite));
}

/* Filtri */
.cat-toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 24px;
  margin: 28px 0 36px;
  padding: 18px 0;
  border-top: 1px solid rgb(var(--v-theme-mist));
  border-bottom: 1px solid rgb(var(--v-theme-mist));
}

.cat-chips {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.cat-chip {
  font: inherit;
  font-size: 12px;
  padding: 8px 18px;
  border-radius: 20px;
  cursor: pointer;
  background: transparent;
  border: 1px solid rgb(var(--v-theme-steel));
  color: rgb(var(--v-theme-graphite));
  transition: 0.2s;
}

.cat-chip:hover {
  border-color: rgb(var(--v-theme-mint));
}

.cat-chip.on {
  background: rgb(var(--v-theme-mint));
  border-color: rgb(var(--v-theme-mint));
  color: #fff;
}

.cat-sort {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 12px;
  color: rgb(var(--v-theme-plum));
  white-space: nowrap;
}

.cat-sort select {
  font: inherit;
  font-size: 12px;
  padding: 8px 12px;
  background: #fff;
  color: rgb(var(--v-theme-ink));
  border: 1px solid rgb(var(--v-theme-mist));
  border-radius: 4px;
  outline: none;
  cursor: pointer;
}

.cat-sort select:focus {
  border-color: rgb(var(--v-theme-moss));
}

/* Režģis */
.cat-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
  gap: 36px 24px;
}

.cat-book {
  cursor: pointer;
  transition: 0.2s;
}

.cat-book:hover {
  transform: translateY(-4px);
}

.cat-img {
  display: block;
  width: 100%;
  aspect-ratio: 2 / 3;
  object-fit: cover;
  box-shadow: 0 8px 20px rgba(35, 31, 29, 0.18);
}

.cat-fallback {
  aspect-ratio: 2 / 3;
  padding: 14px;
  background: rgb(var(--v-theme-plum));
  color: #fff;
  font-family: 'Cormorant Garamond', serif;
  font-size: 22px;
  line-height: 1.1;
}

.cat-book-title {
  margin-top: 14px;
  font-family: 'Cormorant Garamond', serif;
  font-size: 22px;
  line-height: 1.15;
  color: rgb(var(--v-theme-ink));
}

.cat-book-author {
  margin-top: 6px;
  font-size: 14px;
  line-height: 1.4;
  color: rgb(var(--v-theme-plum));
}

.cat-book-rating {
  margin-top: 4px;
  font-size: 14px;
  color: rgb(var(--v-theme-plum));
}

.cat-star {
  color: rgb(var(--v-theme-moss));
}

.cat-empty {
  padding: 60px 0;
  font-size: 14px;
  color: rgb(var(--v-theme-plum));
}

@media (max-width: 960px) {
  .cat-wrap { padding: 0 16px; }
  .cat-title { font-size: 34px; }
  .cat-toolbar { flex-direction: column; align-items: flex-start; }
  .cat-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>