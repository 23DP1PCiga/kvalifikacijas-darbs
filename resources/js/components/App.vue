<template>
  <v-app>
    <v-app-bar color="primary" flat>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />

      <v-toolbar-title>READALOT</v-toolbar-title>

      <v-btn icon="mdi-dots-vertical" variant="text" />
    </v-app-bar>

    <v-navigation-drawer
      v-model="drawer"
      temporary
    >
      <v-list :items="items" />
    </v-navigation-drawer>

    <v-main>
      <RouterView />
    </v-main>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      drawer: false,
      items: [
        { title: 'Home', value: '/' },
        { title: 'Books', value: '/books' },
        { title: 'Login', value: '/login' },
        { title: 'Contact', value: '/contact' },
      ],
    }
  },
}
</script>
