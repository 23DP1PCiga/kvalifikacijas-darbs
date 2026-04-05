<script>
import axios from 'axios'

export default {

  data() {
    return {
      search: '',
      user: null
    }
  },

mounted() {
  const savedUser = localStorage.getItem('user')
  this.user = savedUser ? JSON.parse(savedUser) : null
},

  methods: {

    searchBooks() {
      const query = this.search.trim()

      if (query !== '') {
        this.$router.push({
          path: '/books',
          query: { q: query }
        })
      }
    },

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
    <v-app-bar flat height="100">
      <v-toolbar-title class="px-8 logo">READALOT</v-toolbar-title>
    <v-text-field 
    v-model="search"
    placeholder="Meklēt grāmatas..."
    prepend-inner-icon="mdi-magnify"
    variant="outlined"
    density="comfortable"
    hide-details
    rounded="xl"
    width="500"
    class="mx-10"
    @keyup.enter="searchBooks"
  />

      <div class="d-flex align-center ga-9 px-11">
        <v-btn class="books" to="/books">GRĀMATAS</v-btn>
        <v-btn class="subscription">ABONĒT</v-btn>

        <v-menu v-if="user" offset-y>
        <template v-slot:activator="{ props }">
          <v-btn v-bind="props" class="user">
            {{ user.user_name }}
            <v-icon end>mdi-chevron-down</v-icon>
          </v-btn>
        </template>

        <v-list>

          <v-list-item @click="$router.push('/saved')">
            <v-list-item-title>Saglabātās grāmatas</v-list-item-title>
          </v-list-item>

          <v-list-item @click="$router.push('/comments')">
            <v-list-item-title>Mani komentāri</v-list-item-title>
          </v-list-item>

          <v-list-item @click="$router.push('/ratings')">
            <v-list-item-title>Mani vērtējumi</v-list-item-title>
          </v-list-item>

          <v-divider />

          <v-list-item @click="logout">
            <v-list-item-title class="text-accent">Iziet</v-list-item-title>
          </v-list-item>

        </v-list>
      </v-menu>
      <v-btn v-else class="signup" to="/register">
        REĢISTRĒTIES
      </v-btn>
      
      </div>
    </v-app-bar>

    <v-main>
      <RouterView />
    </v-main>

     
    <v-footer class="app-footer  justify-center">&copy; READALOT 2026</v-footer>
  </v-app>
</template>




