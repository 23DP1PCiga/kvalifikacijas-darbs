<script setup>
import { useRouter } from 'vue-router'
import { ref } from 'vue'
import axios from 'axios'

const user_name = ref('')
const email = ref('')
const password = ref('')
const message = ref('')
const router = useRouter()

const register = async () => {
  try {
    await axios.get('/sanctum/csrf-cookie')

    await axios.post('/register', {
      user_name: user_name.value,
      email: email.value,
      password: password.value

    })


    const res = await axios.get('/user')

    localStorage.setItem('user', JSON.stringify(res.data))

    window.location.href = '/'

  } catch (error) {
    message.value = 'Nepareizs e-pasts vai parole'
  }
}
</script>


<template>
  <v-container max-width="400" class="mt-10">
    <h2>Reģistrācija</h2>

    <v-text-field
      label="Lietotājvārds"
      v-model="user_name"
    />

    <v-text-field
      label="E-pasts"
      v-model="email"
    />

    <v-text-field
      label="Parole"
      type="password"
      v-model="password"
    />

    <v-btn
      color="primary"
      class="mt-4"
      @click="register"
    >Iesniegt</v-btn>

    <p class="mt-4">{{ message }}</p>
  </v-container>
</template>

<style scoped>

h2 {
  font-family: "ABeeZee", sans-serif;
  font-weight: 700;
  text-align: center;
  margin-bottom: 30px;
  color: #424242;
}

.v-container {
  background: white;
  padding: 40px;
  border-radius: 16px;
  box-shadow: 0 8px 30px rgba(0,0,0,0.05);
}

.v-btn {
  height: 48px;
  font-family: "ABeeZee", sans-serif;
  font-weight: 400;
  font-style: normal;
  color: #ffffff;
  font-weight: 400;
  font-style: normal;
}

p {
  text-align: center;
  color: #c2848e;
  font-weight: 500;
}

</style>
