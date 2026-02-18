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
          primary: '#A3A38B',
          secondary: '#000000',
          background: '#ffffff'
        },
      },
    },
  },
})


createApp(App)
  .use(router)
  .use(vuetify)
  .mount('#app')
