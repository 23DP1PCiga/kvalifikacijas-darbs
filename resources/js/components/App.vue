<script>
import { useRouter } from 'vue-router'

export default {

  data() {
    return {
      search: ''
    }
  },

  methods: {

    searchBooks() {

      if (this.search.trim() !== '') {

        this.$router.push(`/books?q=${this.search}`)

      }

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
        <v-btn class="signup" to="/register">REĢISTRĒTIES</v-btn>

      </div>
    </v-app-bar>

    <v-main>
      <RouterView />
    </v-main>

     
    <v-footer class="app-footer  justify-center">&copy; READALOT 2026</v-footer>
  </v-app>
</template>




