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
          primary: '#DFDCCE',
          secondary: '#1c1c1c',
          background: '#ffff'
        },
      },
    },
  },
})


createApp(App)
  .use(router)
  .use(vuetify)
  .mount('#app')
