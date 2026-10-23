<script>
import axios from 'axios'

export default {
  data() {
    return {
      user: null
    }
  },

  // Ielādē pieslēgtā lietotāja datus.
  mounted() {
    const savedUser = localStorage.getItem('user')
    this.user = savedUser ? JSON.parse(savedUser) : null
  },

  methods: {
    // Izraksta lietotāju no sistēmas.
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
    <!-- Galvene ar navigāciju un lietotāja izvēlni. -->
    <v-app-bar flat :height="64" class="rd-bar">
      <div class="rd-header">

        <!-- Logotips, kas ved uz sākumlapu. -->
        <div class="rd-logo" @click="$router.push('/')">
          <span class="rd-logo-mark">
            <i></i><i></i><i></i>
          </span>
          READALOT
        </div>

        <!-- Galvenās sadaļas. -->
        <nav class="rd-nav">
          <router-link
            to="/books"
            class="rd-nav-item"
            :class="{ active: $route.path.startsWith('/books') }"
          >Katalogs</router-link>

          <router-link
            to="/boards"
            class="rd-nav-item"
            :class="{ active: $route.path.startsWith('/boards') }"
          >Kolekcijas</router-link>

          <router-link to="/#features" class="rd-nav-item">Kā tas darbojas</router-link>
        </nav>

        <!-- Pēc pieslēgšanās rāda lietotāja izvēlni, citādi — pieteikšanās pogas. -->
        <div class="rd-actions">
          <v-menu v-if="user" offset-y>
            <template v-slot:activator="{ props }">
              <v-btn v-bind="props" class="rd-user" variant="text">
                {{ user.user_name }}
                <v-icon end>mdi-chevron-down</v-icon>
              </v-btn>
            </template>

            <v-list>
              <!-- Administratora sadaļas. -->
              <v-list-item v-if="user.role === 'admin'" @click="$router.push('/admin')">
                <v-list-item-title>Admin panel</v-list-item-title>
              </v-list-item>

              <v-list-item v-if="user.role === 'admin'" @click="$router.push('/admin/stats')">
                <v-list-item-title>Statistika</v-list-item-title>
              </v-list-item>

              <v-divider v-if="user.role === 'admin'" />

              <!-- Lietotāja personīgās sadaļas. -->
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

              <v-list-item @click="logout">
                <v-list-item-title>Iziet</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>

          <!-- Pogas lietotājiem, kuri vēl nav pieslēgušies. -->
          <template v-else>
            <router-link to="/login" class="rd-login">Pieslēgties</router-link>
            <v-btn class="rd-signup" to="/register" variant="flat">Reģistrēties</v-btn>
          </template>
        </div>
      </div>
    </v-app-bar>

    <!-- Šeit tiek parādīta izvēlētā lapa. -->
    <v-main>
      <RouterView />
    </v-main>

    <!-- Lapas apakšdaļa ar noderīgām saitēm. -->
    <v-footer class="rd-footer">
      <div class="rd-footer-inner">
        <span>&copy; 2026 READALOT</span>
        <div class="rd-footer-links">
          <router-link to="/">Par sistēmu</router-link>
          <router-link to="/">Privātums</router-link>
          <router-link to="/">Lietošanas noteikumi</router-link>
          <router-link to="/">Palīdzība</router-link>
        </div>
      </div>
    </v-footer>
  </v-app>
</template>

<style>
/* Izmantotie fonti: ABeeZee saskarnei un Cormorant Garamond virsrakstiem. */
@import url('https://fonts.googleapis.com/css2?family=ABeeZee:ital@0;1&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap');

:root {
  --rd-font-body: 'ABeeZee', sans-serif;
  --rd-font-display: 'Cormorant Garamond', Georgia, serif;
}

/* Kopējais lapas izskats un pamatteksta fonts. */
html,
body,
.v-application {
  font-family: var(--rd-font-body);
  background: rgb(var(--v-theme-paper));
  color: rgb(var(--v-theme-graphite));
}

.v-application,
.v-application button,
.v-application input,
.v-application textarea,
.v-application .v-btn {
  font-family: var(--rd-font-body);
}

a {
  color: inherit;
  text-decoration: none;
}

/* Galvenes izkārtojums. */
.rd-bar {
  background: rgb(var(--v-theme-paper)) !important;
  border-bottom: 1px solid rgb(var(--v-theme-mist)) !important;
  height: 64px !important;
}

.rd-bar .v-toolbar__content {
  height: 64px !important;
  padding: 0 !important;
}

.rd-header {
  width: 100%;
  height: 64px;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 32px;
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  align-items: center;
}

/* Logotips un tā dekoratīvā ikona. */
.rd-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  justify-self: start;
  cursor: pointer;
  font-family: var(--rd-font-body);
  font-weight: 500;
  font-size: 13px;
  line-height: 1.2;
  letter-spacing: 0.18em;
  color: rgb(var(--v-theme-ink));
}

.rd-logo-mark {
  display: flex;
  align-items: flex-end;
  gap: 2px;
  height: 16px;
}

.rd-logo-mark i {
  display: block;
  width: 4px;
  background: rgb(var(--v-theme-ink));
}

.rd-logo-mark i:nth-child(1) { 
  height: 14px; 
}
.rd-logo-mark i:nth-child(2) { 
  height: 16px; 
  background: rgb(var(--v-theme-mint)); 
}
.rd-logo-mark i:nth-child(3) { 
  height: 12px; 
  background: rgb(var(--v-theme-steel)); 
}

/* Navigācija un aktīvās sadaļas noformējums. */
.rd-nav {
  display: flex;
  gap: 32px;
  justify-self: center;
}

.rd-nav-item {
  font-family: var(--rd-font-body);
  font-size: 12px;
  line-height: 1.6;
  letter-spacing: normal;
  color: rgb(var(--v-theme-plum));
  border-bottom: 1px solid transparent;
  transition: color 0.2s;
}

.rd-nav-item:hover,
.rd-nav-item.active {
  color: rgb(var(--v-theme-ink));
  border-bottom-color: rgb(var(--v-theme-mint));
}

/* Pieteikšanās pogas un lietotāja izvēlne. */
.rd-actions {
  display: flex;
  align-items: center;
  justify-self: end;
  gap: 20px;
}

.rd-login {
  font-family: var(--rd-font-body);
  font-size: 12px;
  font-weight: 500;
  letter-spacing: normal;
  white-space: nowrap;
  color: rgb(var(--v-theme-ink));
}

.rd-signup {
  font-family: var(--rd-font-body);
  background: rgb(var(--v-theme-ink)) !important;
  color: #fff !important;
  border-radius: 4px !important;
  height: 32px !important;
  min-width: 0 !important;
  padding: 0 18px !important;
  font-size: 12px !important;
  font-weight: 500;
  letter-spacing: 0 !important;
  text-transform: none !important;
  white-space: nowrap;
}

.rd-user {
  font-family: var(--rd-font-body);
  text-transform: none !important;
  letter-spacing: 0 !important;
  font-size: 13px !important;
  color: rgb(var(--v-theme-ink));
}

.v-list-item,
.v-list-item-title {
  font-family: var(--rd-font-body);
}

/* Kājenes izkārtojums un saites. */
.rd-footer {
  background: rgb(var(--v-theme-paper)) !important;
  border-top: 1px solid rgb(var(--v-theme-mist));
  padding: 0 !important;
}

.rd-footer-inner {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 18px 32px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-family: var(--rd-font-body);
  font-size: 11px;
  letter-spacing: 0.04em;
  color: rgb(var(--v-theme-plum));
}

.rd-footer-links {
  display: flex;
  gap: 22px;
  letter-spacing: 0;
}

.rd-footer-links a {
  text-decoration: underline;
  text-underline-offset: 3px;
}

/* Vienkāršots izkārtojums mazākiem ekrāniem. */
@media (max-width: 960px) {
  .rd-nav {
    display: none;
  }

  .rd-header {
    padding: 0 16px;
    grid-template-columns: 1fr auto;
  }

  .rd-footer-inner {
    flex-direction: column;
    gap: 10px;
  }
}
</style>