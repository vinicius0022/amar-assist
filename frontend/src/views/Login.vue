<script setup>
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import LoadingOverlay from '@/components/LoadingOverlay.vue'

const email = ref('')
const password = ref('')
const name = ref('')
const isRegister = ref(false)
const isLoading = ref(false)
const error = ref(null)
const router = useRouter()

async function submit() {
  error.value = null
  isLoading.value = true

  try {
    // Pega o cookie CSRF
    await fetch('http://localhost:8000/sanctum/csrf-cookie', {
      credentials: 'include'
    })

    const endpoint = isRegister.value ? 'register' : 'login'

    const body = isRegister.value
      ? JSON.stringify({ name: name.value, email: email.value, password: password.value }) // Inclui o nome no cadastro
      : JSON.stringify({ email: email.value, password: password.value }) // Login apenas com email e senha

    const res = await fetch(`http://localhost:8000/api/${endpoint}`, {
      method: 'POST',
      credentials: 'include',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body
    })

    if (!res.ok) {
      const data = await res.json()
      error.value = data.message || 'Something went wrong'
      return
    }

    router.push('/')
  } catch (err) {
    error.value = 'Network error'
  } finally {
    isLoading.value = false
  }
}

function toggle() {
  isRegister.value = !isRegister.value
  error.value = null
}

</script>

<template>
  <div class="auth-container">
    <h2>{{ isRegister ? 'Cadastrar' : 'Login' }}</h2>

    <form @submit.prevent="submit">
      <div v-if="isRegister">
        <input v-model="name" placeholder="Name" required />
      </div>
      <input v-model="email" placeholder="Email" required />
      <input v-model="password" type="password" placeholder="Password" required />
      <button>{{ isRegister ? 'Cadastrar' : 'Login' }}</button>
    </form>

    <p v-if="error" class="error">{{ error }}</p>

    <p class="switch">
      <span v-if="!isRegister">Não possui uma conta?</span>
      <span v-else>Já possui uma conta?</span>
      <button @click="toggle">{{ isRegister ? 'Login' : 'Cadastrar' }}</button>
    </p>

    <LoadingOverlay v-if="isLoading"/>
  </div>
</template>

<style scoped>
body {
  background: linear-gradient(135deg, #fdfcfb, #e2d1c3);
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  margin: 0;
}

.auth-container {
  background: white;
  padding: 30px 20px 60px; /* Mais espaço na parte de baixo */
  border-radius: 8px;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
  width: 320px;
  text-align: center;
  border-bottom: 30px solid #eaeaea; /* Polaroid bottom border */
  margin: 17% auto;
}

h2 {
  margin-bottom: 20px;
  color: #333;
}

input {
  width: 100%;
  padding: 10px;
  margin-bottom: 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-family: inherit;
  font-size: 14px;
}

button {
  width: 100%;
  background: #333;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 10px;
  transition: background 0.2s ease;
  font-family: inherit;
}

button:hover {
  background: #555;
}

.error {
  color: red;
  margin-top: 10px;
  font-size: 13px;
}

.switch {
  margin-top: 20px;
  font-size: 14px;
  color: #555;
}

.switch button {
  background: none;
  color: #0077cc;
  border: none;
  padding: 0;
  cursor: pointer;
  font-weight: bold;
}

.switch button:hover {
  text-decoration: underline;
}
</style>