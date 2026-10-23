<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const books = ref([])
const search = ref('')
const activeMood = ref(0)

// Atver izvēlētās grāmatas lapu.
const goToBook = (id) => {
  router.push(`/books/${id}`)
}

// Meklēšanas tekstu nosūta uz katalogu.
const searchBooks = () => {
  const query = search.value.trim()

  if (query) {
    router.push({
      path: '/books',
      query: { q: query }
    })
  }
}

// Sākumlapā redzamās iespējas.
const features = [
  {
    n: 'I.',
    title: 'Meklē un filtrē',
    text: 'Plašs grāmatu katalogs ar meklēšanu pēc nosaukuma, autora un tēmas, kā arī filtriem pēc žanra, valodas, gada un vērtējuma.'
  },
  {
    n: 'II.',
    title: 'Personalizēti ieteikumi',
    text: 'READALOT ņem vērā tavas saglabātās un novērtētās grāmatas, lai piedāvātu lasāmvielu, kas atbilst tavām interesēm.'
  },
  {
    n: 'III.',
    title: 'Lasi digitālā vidē',
    text: 'Lasi grāmatas pārlūkā un turpini no vietas, kur iepriekš apstājies.'
  },
  {
    n: 'IV.',
    title: 'Saglabā un vērtē',
    text: 'Veido savu bibliotēku, vērtē grāmatas un raksti atsauksmes citiem lasītājiem.'
  },
  {
    n: 'V.',
    title: 'Tematiskās kolekcijas',
    text: 'Atklāj tematiskas izlases vai izveido savas kolekcijas — rudens vakariem, ceļojumiem vai klasikai.'
  },
  {
    n: 'VI.',
    title: 'Statistika un sasniegumi',
    text: 'Seko saviem lasīšanas paradumiem, izvirzi gada mērķi un krāj sasniegumus.'
  }
]

// Noskaņojumi, pēc kuriem izvēlēties grāmatu.
const moods = [
  {
    label: 'Mierīgs',
    text: 'Lēns, silts stāsts, kas palīdz nomierināties un noslēgt dienu.'
  },
  {
    label: 'Ziņkārīgs',
    text: 'Grāmata, kas atver jaunu skatupunktu un liek uzdot jautājumus.'
  },
  {
    label: 'Nostalģisks',
    text: 'Stāsts par atmiņām, bērnību un laikiem, kas aizgājuši.'
  },
  {
    label: 'Gribu piedzīvojumu',
    text: 'Spraigs sižets, kas neļauj nolikt grāmatu malā.'
  },
  {
    label: 'Melanholisks',
    text: 'Dziļa, domīga lasāmviela vēsiem vakariem.'
  },
  {
    label: 'Gribu pasmaidīt',
    text: 'Viegls, jautrs stāsts, kas uzlabo garastāvokli.'
  }
]

// Sešas visaugstāk novērtētās grāmatas.
const topBooks = computed(() => {
  return [...books.value]
    .sort((a, b) => Number(b.reviews_avg_rating || 0) - Number(a.reviews_avg_rating || 0))
    .slice(0, 6)
})

// Pašlaik izvēlētajam noskaņojumam parāda grāmatu no kataloga.
const pick = computed(() => {
  if (!books.value.length) {
    return null
  }

  return books.value[activeMood.value % books.value.length]
})

// Sākumlapā redzamās tematiskās izlases.
const collections = [
  {
    title: 'Latviešu klasika',
    count: '42 grāmatas',
    colors: ['#56514D', '#231F1D', '#DAD6D4']
  },
  {
    title: 'Rudens vakariem',
    count: '18 grāmatas',
    colors: ['#99CBB5', '#8E8986', '#332E2B']
  },
  {
    title: 'Īsi romāni līdz 200 lpp.',
    count: '27 grāmatas',
    colors: ['#332E2B', '#56514D', '#DAD6D4']
  },
  {
    title: 'Zinātne visiem',
    count: '15 grāmatas',
    colors: ['#231F1D', '#99CBB5', '#8E8986']
  }
]

// Ja vāks saglabāts Laravel storage mapē, izveidojam tā publisko adresi.
const coverSrc = (book) => {
  if (!book.cover) {
    return null
  }

  return book.cover.startsWith('books/')
    ? `/storage/${book.cover}`
    : book.cover
}

// Ielādē grāmatu sarakstu, kad sākumlapa tiek atvērta.
onMounted(async () => {
  try {
    const response = await axios.get('/api/books')
    books.value = response.data
  } catch (error) {
    console.error('Neizdevās ielādēt grāmatas:', error)
  }
})
</script>

<template>
<div class="home">

  <!-- Galvenā sadaļa -->
  <section class="hero">
    <div class="wrap hero-grid">
      <div class="hero-left">
        <div class="eyebrow">Tava personīgā bibliotēka</div>
        <h1>Atrodi, lasi un izbaudi grāmatas <em>vienuviet</em></h1>
        <p class="lead">
          READALOT apvieno grāmatu katalogu, lasīšanu digitālā vidē un
          personalizētus ieteikumus. Saglabā, vērtē un seko savai lasīšanas
          pieredzei — mēs palīdzēsim atrast nākamo grāmatu, kas tieši tev patiks.
        </p>

        <div class="small-label">Meklē grāmatu pēc nosaukuma, autora vai tēmas</div>
        <div class="search-row">
          <div class="search-box">
            <v-icon size="16">mdi-magnify</v-icon>
            <input
              v-model="search"
              placeholder="Nosaukums, autors vai tēma..."
              @keyup.enter="searchBooks"
            />
          </div>
          <button class="btn-light" @click="searchBooks">Meklēt</button>
        </div>

        <div class="hero-actions">
          <v-btn to="/register" class="btn-dark" variant="flat">Izveidot kontu</v-btn>
          <router-link to="/books" class="link-under">Apskatīt katalogu →</router-link>
        </div>
      </div>

      <!-- Grāmatu vāku kolāža -->
      <div class="hero-right">
        <div class="cover c1"><span>Dvēseļu putenis</span><small>A. Grins</small></div>
        <div class="cover c2"><span>Simts vientulības gadi</span><small>G. G. Markess</small></div>
        <div class="cover c3"><span>Mērnieku laiki</span><small>R. un M. Kaudzītes</small></div>
      </div>
    </div>
  </section>

  <!-- Iespējas -->
  <section id="features" class="section features-section">
    <div class="wrap">
      <div class="eyebrow">Ko piedāvā READALOT</div>
      <h2 class="h2">Viss, kas saistīts ar lasīšanu — <em>vienā vietā</em></h2>

      <div class="features">
        <div v-for="f in features" :key="f.n" class="feature">
          <div class="f-num">{{ f.n }}</div>
          <div class="f-title">{{ f.title }}</div>
          <div class="f-text">{{ f.text }}</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Izvēle pēc noskaņojuma -->
  <section class="section mood-section">
    <div class="wrap mood-grid">
      <div>
        <div class="eyebrow">Izvēle pēc noskaņojuma</div>
        <h2 class="h2">Kāda grāmata tev vajadzīga <em>šovakar?</em></h2>
        <p class="muted">Pastāsti, kā jūties — READALOT piemeklēs grāmatu, kas atbilst noskaņojumam.</p>
      </div>

      <div>
        <div class="chips">
          <button
            v-for="(m, i) in moods"
            :key="m.label"
            class="chip"
            :class="{ on: activeMood === i }"
            @click="activeMood = i"
          >{{ m.label }}</button>
        </div>

        <div v-if="pick" class="pick" @click="goToBook(pick.id)">
          <img v-if="coverSrc(pick)" :src="coverSrc(pick)" class="pick-cover" />
          <div v-else class="pick-cover"></div>
          <div>
            <small class="eyebrow">Mūsu ieteikums</small>
            <div class="pick-title">{{ pick.title }}</div>
            <div class="pick-author">{{ pick.author }}</div>
            <p class="muted">{{ moods[activeMood].text }}</p>
            <span class="link-under">Uzzināt vairāk →</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Populārākās grāmatas -->
  <section class="section-popular">
    <div class="wrap">
      <div class="row-between">
        <div>
          <div class="eyebrow">No kataloga</div>
          <h2 class="h2">Šobrīd populāras</h2>
        </div>
        <router-link to="/books" class="link-under">Viss katalogs →</router-link>
      </div>

      <div class="books">
        <div v-for="book in topBooks" :key="book.id" class="book" @click="goToBook(book.id)">
          <img v-if="coverSrc(book)" :src="coverSrc(book)" class="book-img" />
          <div v-else class="book-cover">{{ book.title }}</div>
          <div class="book-title">{{ book.title }}</div>
          <div class="book-meta">
            <span class="star">★</span>
            {{ Number(book.reviews_avg_rating || 0).toFixed(1) }} · {{ book.author }}
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Tematiskās kolekcijas -->
  <section class="section">
    <div class="wrap">
      <div class="eyebrow">Kuratētas izlases</div>
      <h2 class="h2">Tematiskās kolekcijas</h2>

      <div class="collections">
        <div v-for="c in collections" :key="c.title" class="collection">
          <div class="swatches">
            <i v-for="(col, i) in c.colors" :key="i" :style="{ background: col, height: (46 - i * 8) + 'px' }"></i>
          </div>
          <div class="c-title">{{ c.title }}</div>
          <div class="c-count">{{ c.count }}</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Aicinājums izveidot kontu -->
  <section class="cta">
    <div class="wrap cta-grid">
      <div>
        <h2>Tava nākamā grāmata <em>jau gaida</em></h2>
        <p>Izveido kontu, saglabā pirmās grāmatas un saņem ieteikumus, kas pielāgoti tavām interesēm un lasīšanas paradumiem.</p>
      </div>
      <div class="cta-actions">
        <v-btn to="/register" variant="flat" class="cta-fill">Izveidot kontu</v-btn>
        <v-btn to="/login" variant="outlined" class="cta-outline">Man jau ir konts</v-btn>
      </div>
    </div>
  </section>

</div>
</template>

<style scoped>
.home {
  font-family: 'ABeeZee', sans-serif;
}

.wrap {
  max-width: 1200px; 
  margin: 0 auto; 
  padding: 0 32px; 
}
.section { 
  padding: 72px 0 24px; 
}

/* Kopējie teksta stili */
.eyebrow {
  font-size: 10px;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  color: rgb(var(--v-theme-moss));
  margin-bottom: 14px;
}
.h2, h1, .cta h2 {
  font-family: 'Cormorant Garamond', serif;
  font-weight: 400;
  color: rgb(var(--v-theme-ink));
  line-height: 1.2;
}
.h2 { 
  font-size: 34px; 
  margin: 0 0 8px; 
}
h1 { 
  font-size: 52px;
  margin: 0 0 22px;
}
em { 
  font-style: italic; 
  color: rgb(var(--v-theme-moss));
}
.muted { 
  font-size: 13px; 
  line-height: 1.7; 
  color: rgb(var(--v-theme-plum)); 
}
.link-under {
  font-size: 12px;
  color: rgb(var(--v-theme-ink));
  text-decoration: underline;
  text-decoration-color: rgb(var(--v-theme-mint));
  text-underline-offset: 4px;
  cursor: pointer;
}
.row-between { 
  display: flex; 
  justify-content: 
  space-between; 
  align-items: flex-end; 
}

/* Hero */
.hero { 
  padding: 56px 0 72px;
}
.hero-grid { 
  display: grid;
  grid-template-columns: 1.1fr 1fr; 
  gap: 40px; 
  align-items: center; 
}
.lead { 
  font-size: 13px; 
  line-height: 1.8; 
  color: rgb(var(--v-theme-graphite)); 
  max-width: 470px; 
  margin-bottom: 26px; 
}
.small-label { 
  font-size: 10px; 
  color: rgb(var(--v-theme-plum)); 
  margin-bottom: 6px; 
}

.search-row { 
  display: flex; 
  gap: 8px; 
  max-width: 470px; 
}
.search-box {
  flex: 1; 
  display: flex; 
  align-items: center;
  gap: 8px;
  background: #fff; 
  border: 1px solid rgb(var(--v-theme-mist));
  border-radius: 4px; 
  padding: 0 12px; 
  height: 38px;
}
.search-box:focus-within {
   border-color: rgb(var(--v-theme-moss)); 
}
.search-box input { 
  flex: 1; 
  border: 0; 
  outline: 0;
  font: inherit; 
  font-size: 12px; 
  background: transparent; 
  color: rgb(var(--v-theme-ink)); 
}
.search-box input::placeholder {
  color: rgb(var(--v-theme-steel));
}
.btn-light {
  padding: 0 18px; 
  height: 38px; 
  font-size: 12px;
  cursor: pointer;
  background: rgb(var(--v-theme-mint)); ;
  border-radius: 4px; 
  color: rgb(var(--v-theme-ink));
}
.hero-actions { 
  display: flex; 
  align-items: center;
  gap: 22px; 
  margin-top: 22px; 
}
.btn-dark {
  background: rgb(var(--v-theme-ink)) !important; 
  color: #fff !important;
  border-radius: 4px !important;
  height: 38px !important; 
  font-size: 12px !important;
  text-transform: none !important; 
  letter-spacing: 0 !important; 
  padding: 0 22px !important;
}

/* Vāku kolāža */
.hero-right { 
  position: relative; 
  height: 460px;
}
.cover {
  position: absolute; 
  width: 200px; 
  height: 290px; 
  padding: 20px 18px;
  display: flex; 
  flex-direction: column; 
  justify-content: space-between;
  box-shadow: 0 14px 30px rgba(35, 31, 29, 0.25);
  font-family: 'Cormorant Garamond', serif; 
  color: #fff;
}
.cover span { 
  font-size: 29px;
  line-height: 1.1; 
}
.cover small { 
  font-family: 'ABeeZee', sans-serif; 
  font-size: 10px; 
  letter-spacing: 0.18em; 
  text-transform: uppercase; 
  opacity: 0.8; 
}
.c1 { 
  left: 0; 
  top: 50px; 
  background: #56514D; 
  transform: rotate(-4deg); 
}
.c2 { 
  right: 0; 
  top: 0; 
  background: #99CBB5; 
  color: #231F1D; 
  transform: rotate(5deg); 
}
.c3 {
  left: 140px; 
  top: 130px; 
  background: #231F1D;
   width: 225px; 
   height: 325px; 
}
.c3 span { 
  font-size: 34px; 
}

/* Iespējas */
.features-section { 
  background: #fff; 
  padding: 64px 0 72px; 
  margin-top: 0; 
}
.features { 
  display: grid; 
  grid-template-columns: repeat(3, 1fr); 
  column-gap: 48px;
  margin-top: 36px; 
}
.feature { 
  border-top: 1px solid rgb(var(--v-theme-mist)); 
  padding: 18px 0 34px; 
}
.f-num { 
  font-size: 10px; 
  color: rgb(var(--v-theme-moss));
  margin-bottom: 14px; 
}
.f-title { 
  font-family: 'Cormorant Garamond', serif; 
  font-size: 21px; 
  color: rgb(var(--v-theme-ink));
  margin-bottom: 8px; 
}
.f-text { 
  font-size: 11.5px; 
  line-height: 1.7; 
  color: rgb(var(--v-theme-plum)); 
}

/* Noskaņojuma izvēle */
.mood-section { 
  padding: 64px 0; 
}
.mood-grid { 
  display: grid; 
  grid-template-columns: 1fr 1.4fr; 
  gap: 56px; 
  align-items: center; 
}
.chips { 
  display: flex; 
  flex-wrap: wrap; 
  gap: 8px; 
  margin-bottom: 18px; 
}
.chip {
  font-size: 11px;
  padding: 7px 16px; 
  border-radius: 20px; 
  cursor: pointer;
  border: 1px solid rgb(var(--v-theme-steel)); 
  background: transparent;
  color: rgb(var(--v-theme-graphite)); 
  transition: 0.2s;
}
.chip:hover {
  border-color: rgb(var(--v-theme-ink)); 
}
.chip.on { 
  background: rgb(var(--v-theme-mint)); 
  color: #fff; 
  border-color: rgb(var(--v-theme-mint)); 
}
.pick {
  display: flex; 
  gap: 20px; 
  background: #fff; 
  padding: 20px; 
  cursor: pointer;
  border: 1px solid rgb(var(--v-theme-mist)); 
  border-radius: 4px;
}
.pick-cover { 
  width: 70px; 
  height: 100px; 
  object-fit: cover; 
  background: rgb(var(--v-theme-mist)); 
  flex-shrink: 0; 
}
.pick-title { 
  font-family: 'Cormorant Garamond', serif; 
  font-size: 22px; 
  color: rgb(var(--v-theme-ink)); 
}
.pick-author { 
  font-size: 11px; 
  color: rgb(var(--v-theme-plum)); 
  margin-bottom: 6px; 
}

/* Populārās grāmatas */
.books { 
  display: grid; 
  grid-template-columns: repeat(6, 1fr); 
  gap: 20px; 
  margin-top: 28px; 
}
.book { 
  cursor: pointer; 
  transition: 0.2s; 
}
.book:hover { 
  transform: translateY(-4px); 
}
.book-img {
  display: block; 
  width: 100%; aspect-ratio: 2 / 3; 
  object-fit: cover;
  box-shadow: 0 8px 20px rgba(35, 31, 29, 0.18);
}
.book-cover {
  aspect-ratio: 2 / 3; 
  background: rgb(var(--v-theme-plum)); 
  color: #fff; 
  padding: 12px;
  font-family: 'Cormorant Garamond', serif; 
  font-size: 20px;
  line-height: 1.1;
}
.book-title { 
  font-family: 'Cormorant Garamond', serif; 
  font-size: 22px; 
  line-height: 1.15; 
  margin-top: 14px; 
  color: rgb(var(--v-theme-ink)); 
}
.book-meta { 
  font-size: 14px; 
  line-height: 1.4; 
  color: rgb(var(--v-theme-plum)); 
  margin-top: 6px; 
}
.star { 
  color: rgb(var(--v-theme-moss)); 
}

/* Kolekcijas */
.collections { 
  display: grid; 
  grid-template-columns: repeat(4, 1fr); 
  gap: 16px; 
  margin-top: 24px; 
}
.collection { 
  background: #fff; 
  border: 1px solid rgb(var(--v-theme-mist)); 
  padding: 20px; 
}
.swatches { 
  display: flex; 
  align-items: flex-end; 
  gap: 4px; 
  height: 50px; 
  margin-bottom: 16px; 
}
.swatches i { 
  display: block; 
  width: 26px; 
}
.c-title { 
  font-family: 'Cormorant Garamond', serif; 
  font-size: 17px; 
  color: rgb(var(--v-theme-ink)); 
}
.c-count { 
  font-size: 10px; 
  color: rgb(var(--v-theme-plum)); 
  margin-top: 3px; 
}

/* CTA */
.cta { 
  background: rgb(var(--v-theme-ink)); 
  margin-top: 72px; 
  padding: 64px 0; 
}
.cta-grid { 
  display: flex; 
  justify-content: space-between;
  align-items: center; 
  gap: 40px; 
}
.cta h2 { 
  color: #fff; 
  font-size: 36px; 
  margin: 0 0 12px; 
}
.cta em { 
  color: rgb(var(--v-theme-mint)); 
}
.cta p { 
  max-width: 460px; 
  font-size: 12px; 
  line-height: 1.7; 
  color: rgb(var(--v-theme-mist)); 
}
.cta-actions { 
  display: flex; 
  gap: 12px; 
}
.cta-fill {
  background: rgb(var(--v-theme-mint)) !important; 
  color: rgb(var(--v-theme-ink)) !important;
  border-radius: 4px !important; 
  text-transform: none !important; 
  letter-spacing: 0 !important; 
  font-size: 12px !important;
}
.cta-outline {
  border-color: rgb(var(--v-theme-steel)) !important; 
  color: #fff !important;
  border-radius: 4px !important; 
  text-transform: none !important; 
  letter-spacing: 0 !important; 
  font-size: 12px !important;
}

/* Mazāki ekrāni */
@media (max-width: 960px) {
  .hero-grid, .mood-grid { grid-template-columns: 1fr; }
  .hero-right { height: 320px; }
  h1 { font-size: 38px; }
  .features { grid-template-columns: 1fr; }
  .books { grid-template-columns: repeat(2, 1fr); }
  .collections { grid-template-columns: repeat(2, 1fr); }
  .cta-grid { flex-direction: column; align-items: flex-start; }
}
</style>