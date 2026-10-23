<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

axios.defaults.withCredentials = true
axios.defaults.baseURL = 'http://127.0.0.1:8000'

const route = useRoute()
const router = useRouter()

const book = ref(null)
const rating = ref(0)
const newComment = ref('')
const comments = ref([])
const user = ref(null)
const similar = ref([])
const saved = ref(false)
const reviewSort = ref('new')

// Atsauksmju kārtošanas varianti.
const reviewSortItems = [
  { title: 'Jaunākās', value: 'new' },
  { title: 'Augstākais vērtējums', value: 'high' },
  { title: 'Zemākais vērtējums', value: 'low' }
]

// Avatāru krāsas no kopējās paletes.
const avatarColors = ['#231F1D', '#56514D', '#38705A', '#8E8986']

// Ja vāks saglabāts Laravel storage mapē, izveido tā publisko adresi.
const coverSrc = (item) => {
  if (!item?.cover) {
    return null
  }

  return item.cover.startsWith('books/')
    ? `/storage/${item.cover}`
    : item.cover
}

// Grāmatas vidējais vērtējums.
const avg = computed(() => Number(book.value?.reviews_avg_rating || 0))

// Komentāri ar tekstu (vērtējumi bez teksta netiek rādīti kā atsauksmes).
const reviews = computed(() => comments.value.filter(c => c.comment))

// Vērtējumu sadalījums pa zvaigznēm.
const distribution = computed(() => {
  const rated = comments.value.filter(c => c.rating)
  const total = rated.length

  return [5, 4, 3, 2, 1].map(star => {
    const count = rated.filter(c => Math.round(c.rating) === star).length

    return {
      star,
      count,
      percent: total ? Math.round((count / total) * 100) : 0
    }
  })
})

// Atsauksmes izvēlētajā secībā.
const sortedReviews = computed(() => {
  const list = [...reviews.value]

  if (reviewSort.value === 'high') {
    list.sort((a, b) => Number(b.rating || 0) - Number(a.rating || 0))
  } else if (reviewSort.value === 'low') {
    list.sort((a, b) => Number(a.rating || 0) - Number(b.rating || 0))
  } else {
    list.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
  }

  return list
})

// Lietotāja iniciāļi avatāram.
const initials = (name) => {
  const parts = (name || 'User').trim().split(/\s+/)
  return parts.slice(0, 2).map(p => p[0]).join('').toUpperCase()
}

// Atver grāmatas failu lasīšanai.
const readBook = () => {
  if (!book.value?.file) return
  window.open('/storage/' + book.value.file, '_blank')
}

// Dzēš atsauksmi (administrators — jebkuru, lietotājs — savu).
const deleteReview = async (id) => {
  if (user.value?.role === 'admin') {
    await axios.delete('/api/admin/reviews/' + id)
  } else {
    await axios.delete('/api/reviews/' + id)
  }

  comments.value = comments.value.filter(c => c.id !== id)
}

const loadUser = async () => {
  try {
    const res = await axios.get('/user')
    user.value = res.data
  } catch {
    user.value = null
  }
}

const loadBook = async () => {
  const response = await axios.get('/api/books/' + route.params.id)
  book.value = response.data
}

const loadComments = async () => {
  try {
    const res = await axios.get(`/api/books/${route.params.id}/comments`)
    comments.value = Array.isArray(res.data) ? res.data : []
  } catch (e) {
    console.error(e)
    comments.value = []
  }
}

// Līdzīgas grāmatas — tas pats žanrs, izņemot pašreizējo grāmatu.
const loadSimilar = async () => {
  try {
    const res = await axios.get('/api/books')

    similar.value = res.data
      .filter(b => String(b.id) !== String(route.params.id) && b.genre && b.genre === book.value?.genre)
      .slice(0, 6)
  } catch (e) {
    console.error(e)
    similar.value = []
  }
}

const addComment = async () => {
  if (!newComment.value.trim() || rating.value === 0) return

  await axios.get('/sanctum/csrf-cookie')

  await axios.post('/api/comments', {
    book_id: route.params.id,
    comment: newComment.value,
    rating: rating.value
  })

  newComment.value = ''
  rating.value = 0

  await loadComments()
  await loadBook()
}

const saveBook = async () => {
  // Nepieslēgtu lietotāju aizsūta uz pieteikšanos.
  if (!user.value) {
    router.push('/login')
    return
  }

  await axios.get('/sanctum/csrf-cookie')

  await axios.post('/api/saved-books', {
    book_id: route.params.id
  })

  saved.value = true
}

// Ielādē visu lapas saturu.
const loadAll = async () => {
  saved.value = false
  await Promise.all([loadUser(), loadBook(), loadComments()])
  await loadSimilar()
}

onMounted(loadAll)

// Pāriet uz citu grāmatu, nepārlādējot lapu.
watch(() => route.params.id, () => {
  window.scrollTo({ top: 0 })
  loadAll()
})
</script>

<template>
<div v-if="book" class="bk">
  <div class="bk-wrap">

    <!-- Navigācijas ceļš -->
    <nav class="bk-crumbs">
      <router-link to="/books">Katalogs</router-link>
      <template v-if="book.genre">
        <span>/</span>
        <router-link :to="{ path: '/books', query: { genre: book.genre } }">{{ book.genre }}</router-link>
      </template>
      <span>/</span>
      <span class="bk-crumb-current">{{ book.title }}</span>
    </nav>

    <!-- Augšējā daļa: vāks un informācija -->
    <section class="bk-top">

      <div class="bk-side">
        <img v-if="coverSrc(book)" :src="coverSrc(book)" class="bk-cover" />
        <div v-else class="bk-cover bk-cover-fallback">
          <span>{{ book.title }}</span>
          <small>{{ book.author }}</small>
        </div>

        <button class="bk-read" @click="readBook">
          <v-icon size="16">mdi-book-open-variant</v-icon>
          Lasīt tagad
        </button>

        <button class="bk-save" :class="{ on: saved }" @click="saveBook">
          {{ saved ? 'Saglabāts ✓' : 'Saglabāt' }}
        </button>
      </div>

      <div class="bk-info">
        <h1 class="bk-title">{{ book.title }}</h1>
        <div class="bk-author">{{ book.author }}</div>

        <!-- Vērtējums -->
        <div class="bk-rating">
          <v-rating
            :model-value="avg"
            readonly
            half-increments
            density="compact"
            size="20"
            color="steel"
            active-color="moss"
          />
          <span class="bk-rating-value">{{ avg ? avg.toFixed(1) : '0.0' }}</span>
          <span class="bk-rating-count">
            · {{ book.reviews_count || 0 }} vērtējumi · {{ reviews.length }} atsauksmes
          </span>
        </div>

        <!-- Papildu informācija (rāda tikai tos laukus, kas ir datubāzē) -->
        <div class="bk-meta">
          <div v-if="book.genre">
            <small>Žanrs</small>
            <span>{{ book.genre }}</span>
          </div>
          <div v-if="book.year || book.published_year">
            <small>Izdota</small>
            <span>{{ book.year || book.published_year }}</span>
          </div>
          <div v-if="book.pages">
            <small>Apjoms</small>
            <span>{{ book.pages }} lpp.</span>
          </div>
          <div v-if="book.language">
            <small>Valoda</small>
            <span>{{ book.language }}</span>
          </div>
        </div>

        <h2 class="bk-h3">Par grāmatu</h2>
        <p class="bk-desc">{{ book.description }}</p>

        <div v-if="book.genre" class="bk-tags">
          <span>{{ book.genre }}</span>
        </div>
      </div>
    </section>

    <!-- Vērtējumi un atsauksmes -->
    <section class="bk-reviews">

      <aside class="bk-left">
        <h2 class="bk-h2">Vērtējumi</h2>

        <!-- Vērtējumu sadalījums -->
        <div class="bk-card">
          <div v-for="row in distribution" :key="row.star" class="bk-bar-row">
            <span class="bk-bar-label">{{ row.star }} ★</span>
            <div class="bk-bar"><i :style="{ width: row.percent + '%' }"></i></div>
            <span class="bk-bar-pct">{{ row.percent }}%</span>
          </div>
        </div>

        <!-- Lietotāja vērtējuma forma -->
        <div class="bk-card bk-form">
          <div class="bk-form-title">Tavs vērtējums</div>

          <template v-if="user">
            <v-rating
              v-model="rating"
              length="5"
              size="30"
              density="compact"
              color="steel"
              active-color="moss"
            />
            <div class="bk-hint">Pieskaries zvaigznei, lai novērtētu</div>

            <label class="bk-form-label" for="bk-comment">Tava atsauksme</label>
            <textarea
              id="bk-comment"
              v-model="newComment"
              rows="4"
              placeholder="Kas tev patika vai nepatika? Vai ieteiktu citiem?"
            ></textarea>

            <button
              class="bk-submit"
              :disabled="!newComment.trim() || rating === 0"
              @click="addComment"
            >Publicēt atsauksmi</button>
          </template>

          <div v-else class="bk-login-note">
            Lūdzu, <router-link to="/login">piesakieties</router-link>, lai komentētu.
          </div>
        </div>
      </aside>

      <div class="bk-right">
        <div class="bk-right-head">
          <h2 class="bk-h2">Lasītāju atsauksmes</h2>

          <label class="bk-sort">
            <span>Kārtot</span>
            <select v-model="reviewSort">
              <option v-for="item in reviewSortItems" :key="item.value" :value="item.value">
                {{ item.title }}
              </option>
            </select>
          </label>
        </div>

        <div v-if="!sortedReviews.length" class="bk-empty">Nav komentāru</div>

        <article v-for="comment in sortedReviews" :key="comment.id" class="bk-review">
          <div class="bk-review-head">
            <div
              class="bk-avatar"
              :style="{ background: avatarColors[(comment.user_id || comment.id || 0) % avatarColors.length] }"
            >{{ initials(comment.user?.user_name) }}</div>

            <div class="bk-review-who">
              <div class="bk-review-name">{{ comment.user?.user_name || 'User' }}</div>
              <div class="bk-review-sub">
                <v-rating
                  :model-value="comment.rating || 0"
                  readonly
                  density="compact"
                  size="14"
                  color="steel"
                  active-color="moss"
                />
                <span>· {{ new Date(comment.created_at).toLocaleDateString('lv-LV') }}</span>
              </div>
            </div>

            <button
              v-if="user?.role === 'admin' || user?.id === comment.user_id"
              class="bk-delete"
              title="Dzēst"
              @click.stop="deleteReview(comment.id)"
            >
              <v-icon size="18">mdi-delete-outline</v-icon>
            </button>
          </div>

          <p class="bk-review-text">{{ comment.comment }}</p>
        </article>
      </div>
    </section>

    <!-- Līdzīgas grāmatas -->
    <section v-if="similar.length" class="bk-similar">
      <div class="bk-eyebrow">Tas pats žanrs</div>
      <h2 class="bk-h2">Varētu patikt arī</h2>

      <div class="bk-sim-grid">
        <div
          v-for="item in similar"
          :key="item.id"
          class="bk-sim"
          @click="router.push('/books/' + item.id)"
        >
          <img v-if="coverSrc(item)" :src="coverSrc(item)" class="bk-sim-img" />
          <div v-else class="bk-sim-img bk-sim-fallback">{{ item.title }}</div>
          <div class="bk-sim-title">{{ item.title }}</div>
          <div class="bk-sim-author">{{ item.author }}</div>
        </div>
      </div>
    </section>

  </div>
</div>
</template>

<style scoped>
.bk {
  font-family: 'ABeeZee', sans-serif;
  padding: 28px 0 24px;
  color: rgb(var(--v-theme-graphite));
}

.bk-wrap {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 32px;
}

/* Navigācijas ceļš */
.bk-crumbs {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  font-size: 12px;
  color: rgb(var(--v-theme-plum));
}

.bk-crumbs a {
  text-decoration: underline;
  text-underline-offset: 3px;
}

.bk-crumb-current {
  color: rgb(var(--v-theme-ink));
}

/* Augšējā daļa */
.bk-top {
  display: grid;
  grid-template-columns: 290px 1fr;
  gap: 56px;
  margin-top: 36px;
  align-items: start;
}

.bk-cover {
  display: block;
  width: 100%;
  aspect-ratio: 2 / 3;
  object-fit: cover;
  box-shadow: 0 16px 36px rgba(35, 31, 29, 0.22);
}

.bk-cover-fallback {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 22px 20px;
  background: rgb(var(--v-theme-plum));
  color: #fff;
}

.bk-cover-fallback span {
  font-family: 'Cormorant Garamond', serif;
  font-size: 34px;
  line-height: 1.1;
}

.bk-cover-fallback small {
  font-size: 10px;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  opacity: 0.85;
}

.bk-read,
.bk-save {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  height: 44px;
  font: inherit;
  font-size: 13px;
  border-radius: 4px;
  cursor: pointer;
  transition: 0.2s;
}

.bk-read {
  margin-top: 24px;
  background: rgb(var(--v-theme-ink));
  border: 1px solid rgb(var(--v-theme-ink));
  color: #fff;
}

.bk-read:hover {
  background: rgb(var(--v-theme-graphite));
}

.bk-save {
  margin-top: 10px;
  background: #fff;
  border: 1px solid rgb(var(--v-theme-steel));
  color: rgb(var(--v-theme-ink));
}

.bk-save:hover {
  border-color: rgb(var(--v-theme-ink));
}

.bk-save.on {
  background: rgb(var(--v-theme-mint));
  border-color: rgb(var(--v-theme-mint));
}

/* Informācija */
.bk-title {
  font-family: 'Cormorant Garamond', serif;
  font-weight: 400;
  font-size: 56px;
  line-height: 1.15;
  margin: 0 0 8px;
  color: rgb(var(--v-theme-ink));
}

.bk-author {
  font-family: 'Cormorant Garamond', serif;
  font-style: italic;
  font-size: 22px;
  color: rgb(var(--v-theme-plum));
}

.bk-rating {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
  margin: 26px 0 28px;
  font-size: 13px;
}

.bk-rating-value {
  font-size: 18px;
  color: rgb(var(--v-theme-ink));
}

.bk-rating-count {
  color: rgb(var(--v-theme-plum));
}

.bk-meta {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
  padding: 18px 0;
  border-top: 1px solid rgb(var(--v-theme-mist));
  border-bottom: 1px solid rgb(var(--v-theme-mist));
}

.bk-meta:empty {
  display: none;
}

.bk-meta small {
  display: block;
  margin-bottom: 4px;
  font-size: 10px;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: rgb(var(--v-theme-plum));
}

.bk-meta span {
  font-size: 14px;
  color: rgb(var(--v-theme-ink));
}

.bk-h3 {
  margin: 32px 0 12px;
  font-family: 'Cormorant Garamond', serif;
  font-weight: 400;
  font-size: 28px;
  color: rgb(var(--v-theme-ink));
}

.bk-desc {
  max-width: 680px;
  margin: 0;
  font-size: 14px;
  line-height: 1.8;
  color: rgb(var(--v-theme-graphite));
}

.bk-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-top: 20px;
}

.bk-tags span {
  padding: 6px 14px;
  font-size: 12px;
  border: 1px solid rgb(var(--v-theme-steel));
  border-radius: 20px;
  color: rgb(var(--v-theme-graphite));
}

/* Atsauksmes */
.bk-reviews {
  display: grid;
  grid-template-columns: 340px 1fr;
  gap: 32px;
  margin-top: 72px;
  align-items: start;
}

.bk-h2 {
  margin: 0 0 20px;
  font-family: 'Cormorant Garamond', serif;
  font-weight: 400;
  font-size: 36px;
  line-height: 1.2;
  color: rgb(var(--v-theme-ink));
}

.bk-card {
  padding: 24px;
  margin-bottom: 20px;
  background: #fff;
  border: 1px solid rgb(var(--v-theme-mist));
  border-radius: 4px;
}

.bk-bar-row {
  display: grid;
  grid-template-columns: 34px 1fr 40px;
  align-items: center;
  gap: 12px;
  padding: 7px 0;
  font-size: 12px;
  color: rgb(var(--v-theme-plum));
}

.bk-bar {
  height: 4px;
  background: rgb(var(--v-theme-mist));
  border-radius: 2px;
  overflow: hidden;
}

.bk-bar i {
  display: block;
  height: 100%;
  background: rgb(var(--v-theme-moss));
}

.bk-bar-pct {
  text-align: right;
}

/* Forma */
.bk-form-title {
  margin-bottom: 10px;
  font-family: 'Cormorant Garamond', serif;
  font-size: 22px;
  color: rgb(var(--v-theme-ink));
}

.bk-hint {
  margin: 8px 0 16px;
  font-size: 11px;
  font-style: italic;
  color: rgb(var(--v-theme-plum));
}

.bk-form-label {
  display: block;
  margin-bottom: 8px;
  font-size: 12px;
  color: rgb(var(--v-theme-ink));
}

.bk-form textarea {
  display: block;
  width: 100%;
  padding: 12px;
  font: inherit;
  font-size: 13px;
  line-height: 1.5;
  resize: vertical;
  background: rgb(var(--v-theme-paper));
  color: rgb(var(--v-theme-ink));
  border: 1px solid rgb(var(--v-theme-mist));
  border-radius: 4px;
  outline: none;
}

.bk-form textarea:focus {
  border-color: rgb(var(--v-theme-moss));
}

.bk-form textarea::placeholder {
  color: rgb(var(--v-theme-plum));
}

.bk-submit {
  margin-top: 14px;
  padding: 0 20px;
  height: 38px;
  font: inherit;
  font-size: 12px;
  color: #fff;
  background: rgb(var(--v-theme-ink));
  border: 0;
  border-radius: 4px;
  cursor: pointer;
  transition: 0.2s;
}

.bk-submit:hover:not(:disabled) {
  background: rgb(var(--v-theme-graphite));
}

.bk-submit:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.bk-login-note {
  font-size: 13px;
  line-height: 1.6;
  color: rgb(var(--v-theme-plum));
}

.bk-login-note a {
  color: rgb(var(--v-theme-ink));
  text-decoration: underline;
  text-underline-offset: 3px;
}

/* Atsauksmju saraksts */
.bk-right-head {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 16px;
}

.bk-sort {
  display: flex;
  align-items: center;
  gap: 10px;
  padding-top: 10px;
  font-size: 12px;
  color: rgb(var(--v-theme-plum));
  white-space: nowrap;
}

.bk-sort select {
  padding: 8px 12px;
  font: inherit;
  font-size: 12px;
  background: #fff;
  color: rgb(var(--v-theme-ink));
  border: 1px solid rgb(var(--v-theme-mist));
  border-radius: 4px;
  outline: none;
  cursor: pointer;
}

.bk-empty {
  padding: 28px;
  font-size: 14px;
  color: rgb(var(--v-theme-plum));
  background: #fff;
  border: 1px solid rgb(var(--v-theme-mist));
  border-radius: 4px;
}

.bk-review {
  padding: 24px 28px;
  margin-bottom: 16px;
  background: #fff;
  border: 1px solid rgb(var(--v-theme-mist));
  border-radius: 4px;
}

.bk-review-head {
  display: flex;
  align-items: center;
  gap: 14px;
}

.bk-avatar {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  flex-shrink: 0;
  font-size: 12px;
  color: #fff;
  border-radius: 50%;
}

.bk-review-who {
  flex: 1;
  min-width: 0;
}

.bk-review-name {
  font-size: 14px;
  color: rgb(var(--v-theme-ink));
}

.bk-review-sub {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 11px;
  color: rgb(var(--v-theme-plum));
}

.bk-delete {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  flex-shrink: 0;
  color: rgb(var(--v-theme-plum));
  background: transparent;
  border: 1px solid rgb(var(--v-theme-mist));
  border-radius: 50%;
  cursor: pointer;
  transition: 0.2s;
}

.bk-delete:hover {
  color: rgb(var(--v-theme-ink));
  border-color: rgb(var(--v-theme-ink));
}

.bk-review-text {
  margin: 14px 0 0;
  font-size: 14px;
  line-height: 1.75;
  color: rgb(var(--v-theme-graphite));
  word-break: break-word;
  overflow-wrap: break-word;
}

/* Līdzīgas grāmatas */
.bk-similar {
  margin-top: 72px;
  padding-top: 40px;
  border-top: 1px solid rgb(var(--v-theme-mist));
}

.bk-eyebrow {
  margin-bottom: 14px;
  font-size: 10px;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  color: rgb(var(--v-theme-moss));
}

.bk-sim-grid {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 24px;
}

.bk-sim {
  cursor: pointer;
  transition: 0.2s;
}

.bk-sim:hover {
  transform: translateY(-4px);
}

.bk-sim-img {
  display: block;
  width: 100%;
  aspect-ratio: 2 / 3;
  object-fit: cover;
  box-shadow: 0 8px 20px rgba(35, 31, 29, 0.18);
}

.bk-sim-fallback {
  padding: 12px;
  background: rgb(var(--v-theme-plum));
  color: #fff;
  font-family: 'Cormorant Garamond', serif;
  font-size: 18px;
  line-height: 1.1;
  box-shadow: none;
}

.bk-sim-title {
  margin-top: 12px;
  font-family: 'Cormorant Garamond', serif;
  font-size: 19px;
  line-height: 1.15;
  color: rgb(var(--v-theme-ink));
}

.bk-sim-author {
  margin-top: 4px;
  font-size: 13px;
  color: rgb(var(--v-theme-plum));
}

@media (max-width: 960px) {
  .bk-wrap { padding: 0 16px; }
  .bk-top { grid-template-columns: 1fr; gap: 32px; }
  .bk-side { max-width: 290px; }
  .bk-title { font-size: 40px; }
  .bk-meta { grid-template-columns: repeat(2, 1fr); }
  .bk-reviews { grid-template-columns: 1fr; }
  .bk-sim-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>