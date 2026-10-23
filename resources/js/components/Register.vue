<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

// Lauku nosaukumi latviski (servera ziņojumos tie ir angliski).
const fieldNames = {
  first_name: 'vārds',
  last_name: 'uzvārds',
  user_name: 'lietotājvārds',
  email: 'e-pasts',
  password: 'parole',
  password_confirmation: 'paroles apstiprinājums',
  login: 'e-pasts vai lietotājvārds',
}

const fieldName = (raw) =>
  fieldNames[raw.trim().toLowerCase().replace(/\s+/g, '_')] || raw

// Pārtulko servera (Laravel) kļūdu ziņojumus latviski.
const translateError = (message) => {
  const fallback = 'Pārbaudi ievadītos datus un mēģini vēlreiz.'

  if (typeof message !== 'string' || !message.trim()) return fallback

  const m = message.trim()
  let r

  // Ziņojums jau latviski (ir latviešu burti vai tipiski vārdi).
  if (/[āčēģīķļņšūž]/i.test(m) || /\b(nepareizs|parole|paroli|pasts|lauks|jābūt|nav|lūdzu)\b/i.test(m)) {
    return m
  }

  if ((r = m.match(/^The (.+?) field is required\.?$/i))) {
    return `Lauks “${fieldName(r[1])}” ir obligāts.`
  }
  if ((r = m.match(/^The (.+?) has already been taken\.?$/i))) {
    return `Lauka “${fieldName(r[1])}” vērtība jau tiek izmantota.`
  }
  if (/valid email address/i.test(m)) return 'Lūdzu, ievadi derīgu e-pasta adresi.'
  if (/confirmation does not match/i.test(m)) return 'Paroles nesakrīt.'
  if ((r = m.match(/^The (.+?) (?:field )?must be at least (\d+) characters\.?$/i))) {
    return `Lauka “${fieldName(r[1])}” garumam jābūt vismaz ${r[2]} rakstzīmēm.`
  }
  if ((r = m.match(/^The (.+?) (?:field )?(?:may not be greater than|must not be greater than) (\d+) characters\.?$/i))) {
    return `Lauka “${fieldName(r[1])}” garums nedrīkst pārsniegt ${r[2]} rakstzīmes.`
  }
  if (/uppercase and one lowercase/i.test(m)) return 'Parolei jāsatur gan lielie, gan mazie burti.'
  if (/at least one letter/i.test(m)) return 'Parolei jāsatur vismaz viens burts.'
  if (/at least one number/i.test(m)) return 'Parolei jāsatur vismaz viens cipars.'
  if (/at least one symbol/i.test(m)) return 'Parolei jāsatur vismaz viens simbols.'
  if (/data leak/i.test(m)) return 'Šī parole ir atrasta datu noplūdēs. Izvēlies citu paroli.'
  if (/too many/i.test(m)) return 'Pārāk daudz mēģinājumu. Mēģini vēlreiz vēlāk.'

  return fallback
}

const router = useRouter()

const form = ref({
  first_name: '',
  last_name: '',
  user_name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const showPassword = ref(false)
const errors = ref({})
const loading = ref(false)

// Izveido jaunu kontu un uzreiz pieslēdz lietotāju.
const register = async () => {
  errors.value = {}
  loading.value = true

  try {
    await axios.get('/sanctum/csrf-cookie')

    const response = await axios.post('/register', form.value)

    localStorage.setItem('user', JSON.stringify(response.data.user))

    await router.push('/')
  } catch (error) {
    if (error.response?.status === 422) {
      const serverErrors = error.response.data.errors

      if (serverErrors) {
        // Katra lauka kļūdas pārtulko latviski.
        errors.value = Object.fromEntries(
          Object.entries(serverErrors).map(([key, messages]) => [
            key,
            [].concat(messages).map(translateError),
          ])
        )
      } else {
        errors.value = {
          general: [translateError(error.response.data.message)],
        }
      }
    } else if (error.response?.status === 429) {
      errors.value = {
        general: ['Pārāk daudz mēģinājumu. Mēģini vēlreiz vēlāk.'],
      }
    } else {
      errors.value = {
        general: ['Radās kļūda. Lūdzu, mēģini vēlreiz.'],
      }
    }
  } finally {
    loading.value = false
  }
}

// Ieguvumi, kas redzami kreisajā sadaļā.
const perks = [
  'Saglabā grāmatas savā bibliotēkā',
  'Vērtē un raksti atsauksmes',
  'Saņem personalizētus ieteikumus'
]
</script>

<template>
  <div class="auth">

    <!-- Kreisā tumšā sadaļa ar ieguvumiem -->
    <aside class="auth-side">
      <router-link to="/" class="auth-brand">
        <span class="auth-mark"><i></i><i></i><i></i></span>
        READALOT
      </router-link>

      <div class="auth-pitch">
        <div class="auth-eyebrow">Pievienojies lasītājiem</div>
        <h2>Sāc savu <em>lasīšanas ceļu</em></h2>

        <ul class="auth-perks">
          <li v-for="perk in perks" :key="perk">
            <v-icon size="18">mdi-check</v-icon>
            {{ perk }}
          </li>
        </ul>
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
        <div class="auth-eyebrow dark">Reģistrācija</div>
        <h1 class="auth-title">Izveido savu <em>kontu</em></h1>
        <p class="auth-sub">Pievienojies lasītāju kopienai un atrodi savu nākamo grāmatu.</p>

        <v-alert
          v-if="errors.general"
          type="error"
          variant="tonal"
          density="comfortable"
          class="mb-5"
        >
          {{ errors.general[0] }}
        </v-alert>

        <v-form class="auth-form" @submit.prevent="register">
          <v-row dense>
            <v-col cols="12" sm="6">
              <v-text-field
                v-model="form.first_name"
                label="Vārds"
                placeholder="Tavs vārds"
                variant="outlined"
                color="moss"
                autocomplete="given-name"
                :error-messages="errors.first_name"
              />
            </v-col>

            <v-col cols="12" sm="6">
              <v-text-field
                v-model="form.last_name"
                label="Uzvārds"
                placeholder="Tavs uzvārds"
                variant="outlined"
                color="moss"
                autocomplete="family-name"
                :error-messages="errors.last_name"
              />
            </v-col>
          </v-row>

          <v-text-field
            v-model="form.user_name"
            label="Lietotājvārds"
            placeholder="Izvēlies lietotājvārdu"
            variant="outlined"
            color="moss"
            autocomplete="username"
            prepend-inner-icon="mdi-account-outline"
            :error-messages="errors.user_name"
          />

          <v-text-field
            v-model="form.email"
            label="E-pasts"
            placeholder="vards@example.com"
            type="email"
            variant="outlined"
            color="moss"
            autocomplete="email"
            prepend-inner-icon="mdi-email-outline"
            :error-messages="errors.email"
          />

          <v-text-field
            v-model="form.password"
            label="Parole"
            :type="showPassword ? 'text' : 'password'"
            variant="outlined"
            color="moss"
            autocomplete="new-password"
            prepend-inner-icon="mdi-lock-outline"
            :append-inner-icon="showPassword ? 'mdi-eye-off-outline' : 'mdi-eye-outline'"
            :error-messages="errors.password"
            @click:append-inner="showPassword = !showPassword"
          />

          <v-text-field
            v-model="form.password_confirmation"
            label="Apstiprini paroli"
            :type="showPassword ? 'text' : 'password'"
            variant="outlined"
            color="moss"
            autocomplete="new-password"
            prepend-inner-icon="mdi-lock-check-outline"
          />

          <v-btn
            type="submit"
            block
            class="auth-submit"
            :loading="loading"
            :disabled="loading"
          >
            Izveidot kontu
          </v-btn>
        </v-form>

        <p class="auth-switch">
          Tev jau ir konts?
          <router-link to="/login">Pieslēgties</router-link>
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
  margin: 0 0 24px;
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

.auth-perks {
  display: flex;
  flex-direction: column;
  gap: 14px;
  margin: 0;
  padding: 0;
  list-style: none;
  font-size: 14px;
  color: rgb(var(--v-theme-mist));
}

.auth-perks li {
  display: flex;
  align-items: center;
  gap: 12px;
}

.auth-perks .v-icon {
  color: rgb(var(--v-theme-mint));
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
  max-width: 460px;
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

.auth-form :deep(.v-field:not(.v-field--focused):not(.v-field--error) .v-field__outline) {
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