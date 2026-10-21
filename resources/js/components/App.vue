```vue
<script>
import axios from 'axios'

export default {

  data() {
    return {
      search: '', // Meklēšanas laukā ievadītais teksts
      user: null // Informācija par pašlaik pieslēgto lietotāju
    }
  },

  // Ielādē lietotāja datus no localStorage, kad tiek atvērta lapa
  mounted() {
    const savedUser = localStorage.getItem('user')
    this.user = savedUser ? JSON.parse(savedUser) : null
  },

  methods: {

    // Meklē grāmatas pēc lietotāja ievadītā atslēgvārda
    searchBooks() {
      const query = this.search.trim() 
      if (query !== '') {
        this.$router.push({
          path: '/books',
          query: { q: query }
        })
        this.search = ''
      }
    },

    // Izraksta lietotāju no sistēmas
    async logout() {
      await axios.get('/sanctum/csrf-cookie')
      await axios.post('/logout')
      this.user = null
      localStorage.removeItem('user') 
      this.$router.push('/') 
    }
  }
}
</script>

<template>
  <v-app>
    <!-- Lapas galvene -->
    <v-app-bar flat height="183" class="app-header">
      <div class="header">

        <v-toolbar-title
          class="logo"
          style="cursor:pointer"
          @click="$router.push('/')">
          READALOT
        </v-toolbar-title>

        <!-- Meklēšanas lauks grāmatu meklēšanai -->
        <v-text-field
          v-model="search"
          placeholder="Meklēt pēc atslēgvārda..."
          append-inner-icon="mdi-magnify"
          variant="solo-filled"
          flat
          density="comfortable"
          hide-details
          rounded="lg"
          class="search"
          @keyup.enter="searchBooks"
          @click:append-inner="searchBooks"
        />

        <!-- Galvenes pogas un lietotāja izvēlne -->
        <div class="header-actions">

          <v-menu v-if="user" offset-y>
            <template v-slot:activator="{ props }">
              <v-btn v-bind="props" class="user">
                {{ user.user_name }}
                <v-icon end>mdi-chevron-down</v-icon>
              </v-btn>
            </template>

            <v-list>
              <!-- Administratora sadaļas ir redzamas tikai administratoram -->
              <v-list-item
                v-if="user.role === 'admin'"
                @click="$router.push('/admin')"
              >
                <v-list-item-title>Admin panel</v-list-item-title>
              </v-list-item>

              <v-list-item
                v-if="user.role === 'admin'"
                @click="$router.push('/admin/stats')"
              >
                <v-list-item-title>Statistika</v-list-item-title>
              </v-list-item>

              <v-divider v-if="user.role === 'admin'" />

              <!-- Saites uz lietotāja personīgajām sadaļām -->
              <v-list-item @click="$router.push('/saved')">
                <v-list-item-title>Saglabātās grāmatas</v-list-item-title>
              </v-list-item>

              <v-list-item @click="$router.push('/boards')">
                <v-list-item-title>Kolekcijas</v-list-item-title>
              </v-list-item>

              <v-list-item @click="$router.push('/comments')">
                <v-list-item-title>Mani komentāri</v-list-item-title>
              </v-list-item>

              <v-divider />

              <!-- Poga, lai izrakstītos no sistēmas -->
              <v-list-item @click="logout">
                <v-list-item-title class="text-accent">Iziet</v-list-item-title>
              </v-list-item>

            </v-list>
          </v-menu>

          <!-- Ja lietotājs nav pieslēdzies, tiek parādītas pieteikšanās pogas -->
          <template v-else>
            <router-link to="/login" class="login-link">Ieiet</router-link>
            <v-btn class="signup" to="/register" variant="flat">
              Reģistrēties
            </v-btn>
          </template>

          <div class="lang"><b>LV</b> | EN</div>
        </div>

        <!-- Galvenā navigācija -->
        <nav class="nav">
          <router-link
            to="/"
            class="nav-item"
            :class="{ active: $route.path === '/' }"
          >Sākums</router-link>

          <router-link
            to="/books"
            class="nav-item"
            :class="{ active: $route.path.startsWith('/books') }"
          >Katalogs</router-link>

          <router-link
            to="/boards"
            class="nav-item"
            :class="{ active: $route.path.startsWith('/boards') }"
          >Kolekcijas</router-link>
        </nav>

      </div>
    </v-app-bar>

    <!-- Šeit tiek parādīts izvēlētās lapas saturs -->
    <v-main>
      <RouterView />
    </v-main>

    <!-- Lapas apakšdaļa -->
    <v-footer class="app-footer justify-center">
      &copy; READALOT 2026
    </v-footer>
  </v-app>
</template>
