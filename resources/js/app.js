import './bootstrap'
import { createApp } from 'vue'
import App from './components/App.vue'
import router from './router'

import { createVuetify } from 'vuetify'
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'

import '../css/app.css'

const vuetify = createVuetify({
  theme: {
    defaultTheme: 'light',
    themes: {
      light: {
        colors: {
          primary: '#231F1D',     // galvenā tumšā krāsa (pogas, virsraksti)
          secondary: '#56514D',   // otrā krāsa
          accent: '#99CBB5',      // piparmētru akcents
          background: '#F4F2F1',  // lapas fons (ļoti gaiša #DAD6D4 nokrāsa)
          surface: '#FFFFFF',

          ink: '#231F1D',         // virsraksti, tumšās sadaļas, pogas
          graphite: '#332E2B',    // pamatteksts
          plum: '#56514D',        // sekundārais teksts
          steel: '#8E8986',       // apmales, neitrāli elementi, teksts uz tumša fona
          mist: '#DAD6D4',        // gaiši pelēks (līnijas, lauki, pogas)
          mint: '#99CBB5',        // akcents uz tumša fona (CTA, logotips, vāki)
          moss: '#38705A',        // papildu akcents: tumšāka mint nokrāsa tekstam uz gaiša fona
          paper: '#F4F2F1',
        },
      },
    },
  },
})

createApp(App)
  .use(router).use(vuetify)
  .mount('#app')