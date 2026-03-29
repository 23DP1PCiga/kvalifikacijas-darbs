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

  if (savedUser) {
    this.user = JSON.parse(savedUser)
  } else {
    axios.get('/user')
      .then(res => this.user = res.data)
      .catch(() => this.user = null)
  }
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
        <v-btn v-if="user">
        {{ user.user_name }}
      </v-btn>
      <v-btn v-else class="signup" to="/register">
        REĢISTRĒTIES
      </v-btn>
      <v-btn v-if="user" @click="logout">
        LOGOUT
      </v-btn>
      </div>
    </v-app-bar>

    <v-main>
      <RouterView />
    </v-main>

     
    <v-footer class="app-footer  justify-center">&copy; READALOT 2026</v-footer>
  </v-app>
</template>




