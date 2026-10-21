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
          primary: '#828270',
          secondary: '#000000',
          background: '#ffffff',
          accent: '#c2848e',
          brand: '#3d3589',
          beige: '#f3e6dc',
          lavender: '#e8e6f4',
          cream: '#f7f3ec',
        },
      },
    },
  },
})


createApp(App)
  .use(router).use(vuetify)
  .mount('#app')
