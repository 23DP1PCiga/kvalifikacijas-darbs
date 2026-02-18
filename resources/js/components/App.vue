<template>
  <v-app>
    <v-app-bar flat height="100">
      <v-toolbar-title class="px-8 logo">READALOT</v-toolbar-title>
      
      <div class="d-flex align-center ga-7 px-9">
        <v-btn class="books">GRĀMATAS</v-btn>
        <v-btn class="subscription">ABONĒT</v-btn>
        <v-btn class="signup">REĢISTRĒTIES</v-btn>
      </div>
    </v-app-bar>

    <v-main>
      <RouterView />
    </v-main>

     
    <v-footer class="app-footer  justify-center">&copy; READALOT 2026</v-footer>
  </v-app>
</template>


