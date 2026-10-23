<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const login = ref('')
const password = ref('')
const showPassword = ref(false)
const errorMessage = ref('')
const loading = ref(false)

// Pieslēdz lietotāju un saglabā tā datus pārlūkā.
const signIn = async () => {
  errorMessage.value = ''
  loading.value = true

  try {
    await axios.get('/sanctum/csrf-cookie')

    const response = await axios.post('/login', {
      login: login.value,
      password: password.value,
    })

    localStorage.setItem('user', JSON.stringify(response.data.user))

    await router.push('/')
  } catch (error) {
    if (error.response?.status === 422) {
      errorMessage.value =
        error.response.data.message ||
        Object.values(error.response.data.errors ?? {}).flat()[0] ||
        'Nepareizs e-pasts vai parole.'
    } else {
      errorMessage.value = 'Neizdevās pieslēgties. Mēģini vēlreiz.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="auth">

    <!-- Kreisā tumšā sadaļa ar noskaņu -->
    <aside class="auth-side">
      <router-link to="/" class="auth-brand">
        <span class="auth-mark"><i></i><i></i><i></i></span>
        READALOT
      </router-link>

      <div class="auth-pitch">
        <div class="auth-eyebrow">Tava personīgā bibliotēka</div>
        <h2>Prieks tevi <em>atkal redzēt</em></h2>
        <p>Turpini lasīt tur, kur apstājies — tavas grāmatas un ieteikumi jau gaida.</p>
      </div>

      <div class="auth-covers" aria-hidden="true">
        <div class="ac ac1"></div>
        <div class="ac ac2"></div>
        <div class="ac ac3"></div>
      </div>
    </aside>

    <!-- Labā sadaļa ar formu -->
    <main class="auth-main">
      <div class="auth-box">
        <div class="auth-eyebrow dark">Pieteikšanās</div>
        <h1 class="auth-title">Laipni lūdzam <em>atpakaļ</em></h1>
        <p class="auth-sub">Pieslēdzies savam kontam un turpini lasīt.</p>

        <v-alert
          v-if="errorMessage"
          type="error"
          variant="tonal"
          density="comfortable"
          class="mb-5"
        >
          {{ errorMessage }}
        </v-alert>

        <v-form class="auth-form" @submit.prevent="signIn">
          <v-text-field
            v-model="login"
            label="E-pasts vai lietotājvārds"
            placeholder="Ievadi e-pastu vai lietotājvārdu"
            variant="outlined"
            color="moss"
            autocomplete="username"
            prepend-inner-icon="mdi-account-outline"
            required
          />

          <v-text-field
            v-model="password"
            label="Parole"
            :type="showPassword ? 'text' : 'password'"
            variant="outlined"
            color="moss"
            autocomplete="current-password"
            prepend-inner-icon="mdi-lock-outline"
            :append-inner-icon="showPassword ? 'mdi-eye-off-outline' : 'mdi-eye-outline'"
            @click:append-inner="showPassword = !showPassword"
            required
          />

          <v-btn
            type="submit"
            block
            class="auth-submit"
            :loading="loading"
            :disabled="loading"
          >
            Pieslēgties
          </v-btn>
        </v-form>

        <p class="auth-switch">
          Vēl nav konta?
          <router-link to="/register">Reģistrēties</router-link>
        </p>
      </div>
    </main>

  </div>
</template>

<style scoped>
.auth {
  display: grid;
  grid-template-columns: 5fr 6fr;
  min-height: calc(100vh - 122px);
  font-family: 'ABeeZee', sans-serif;
}

/* Kreisā sadaļa */
.auth-side {
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 44px 56px;
  overflow: hidden;
  background: rgb(var(--v-theme-ink));
  color: #fff;
}

.auth-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  width: fit-content;
  font-size: 13px;
  font-weight: 500;
  letter-spacing: 0.18em;
  color: #fff;
}

.auth-mark {
  display: flex;
  align-items: flex-end;
  gap: 2px;
  height: 16px;
}

.auth-mark i { 
    display: block; 
    width: 4px; 
    background: #fff; 
}
.auth-mark i:nth-child(1) { 
    height: 14px;
}
.auth-mark i:nth-child(2) { 
    height: 16px; 
    background: rgb(var(--v-theme-mint));
}
.auth-mark i:nth-child(3) { 
    height: 12px; 
    background: rgb(var(--v-theme-steel)); 
}

.auth-pitch {
  position: relative;
  z-index: 1;
  max-width: 420px;
  margin: 56px 0;
}

.auth-eyebrow {
  margin-bottom: 16px;
  font-size: 10px;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  color: rgb(var(--v-theme-mint));
}

.auth-eyebrow.dark {
  color: rgb(var(--v-theme-moss));
}

.auth-pitch h2 {
  margin: 0 0 16px;
  font-family: 'Cormorant Garamond', serif;
  font-weight: 400;
  font-size: 48px;
  line-height: 1.15;
  color: #fff;
}

.auth-pitch h2 em {
  font-style: italic;
  color: rgb(var(--v-theme-mint));
}

.auth-pitch p {
  margin: 0;
  font-size: 14px;
  line-height: 1.8;
  color: rgb(var(--v-theme-mist));
}

/* Dekoratīvie grāmatu vāki */
.auth-covers {
  position: relative;
  height: 210px;
}

.ac {
  position: absolute;
  bottom: -40px;
  width: 130px;
  height: 190px;
  box-shadow: 0 14px 30px rgba(0, 0, 0, 0.35);
}

.ac1 { 
    left: 0; 
    background: rgb(var(--v-theme-plum)); 
    transform: rotate(-6deg); 
}
.ac2 { 
    left: 100px; 
    bottom: -20px; 
    background: rgb(var(--v-theme-mint));
     transform: rotate(3deg); 
}
.ac3 { 
    left: 200px; 
    bottom: -48px; 
    background: rgb(var(--v-theme-graphite)); 
    transform: rotate(-3deg); 
    border: 1px solid rgba(255, 255, 255, 0.12); 
}

/* Labā sadaļa */
.auth-main {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 48px 32px;
  background: rgb(var(--v-theme-paper));
}

.auth-box {
  width: 100%;
  max-width: 420px;
}

.auth-title {
  margin: 0 0 10px;
  font-family: 'Cormorant Garamond', serif;
  font-weight: 400;
  font-size: 44px;
  line-height: 1.15;
  color: rgb(var(--v-theme-ink));
}

.auth-title em {
  font-style: italic;
  color: rgb(var(--v-theme-moss));
}

.auth-sub {
  margin: 0 0 32px;
  font-size: 14px;
  line-height: 1.7;
  color: rgb(var(--v-theme-plum));
}

/* Ievades lauki */
.auth-form :deep(.v-field) {
  border-radius: 4px;
  background: #fff;
  font-size: 14px;
  color: rgb(var(--v-theme-ink));
}

.auth-form :deep(.v-field:not(.v-field--focused) .v-field__outline) {
  color: rgb(var(--v-theme-steel));
}

.auth-form :deep(.v-label) {
  font-size: 14px;
}

.auth-form :deep(.v-field__prepend-inner > .v-icon),
.auth-form :deep(.v-field__append-inner > .v-icon) {
  color: rgb(var(--v-theme-plum));
  opacity: 1;
}

/* Poga */
.auth-submit {
  height: 48px !important;
  margin-top: 8px;
  font-family: 'ABeeZee', sans-serif;
  font-size: 14px !important;
  letter-spacing: 0 !important;
  text-transform: none !important;
  border-radius: 4px !important;
  background: rgb(var(--v-theme-ink)) !important;
  color: #fff !important;
  box-shadow: none !important;
}

.auth-submit:hover {
  background: rgb(var(--v-theme-graphite)) !important;
}

.auth-switch {
  margin: 28px 0 0;
  text-align: center;
  font-size: 13px;
  color: rgb(var(--v-theme-plum));
}

.auth-switch a {
  margin-left: 4px;
  color: rgb(var(--v-theme-ink));
  text-decoration: underline;
  text-decoration-color: rgb(var(--v-theme-mint));
  text-underline-offset: 4px;
}

@media (max-width: 900px) {
  .auth { grid-template-columns: 1fr; }
  .auth-side { display: none; }
  .auth-main { padding: 40px 16px; }
  .auth-title { font-size: 36px; }
}
</style>