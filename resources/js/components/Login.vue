<template>
  <div class="container mt-5" style="max-width: 400px;">
    <div class="card shadow p-4">
      <h2 class="text-center mb-4">Iniciar Sesión</h2>

      <div class="mb-3">
        <input
          v-model="email"
          type="email"
          class="form-control"
          placeholder="Correo electrónico"
        >
      </div>

      <div class="mb-3">
        <input
          v-model="password"
          type="password"
          class="form-control"
          placeholder="Contraseña"
        >
      </div>

      <button @click="login" class="btn btn-primary w-100">
        Ingresar
      </button>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    async login() {
      if (!this.email || !this.password) {
        return alert('Complete todos los campos')
      }

      try {
        const { data } = await axios.post('/api/login', {
          email: this.email,
          password: this.password
        })

        localStorage.setItem('usuario', JSON.stringify(data.usuario))

        alert('Bienvenido ' + data.usuario.name)

        this.$router.push('/')
      } catch (error) {
        alert(error.response?.data?.mensaje || 'Error al iniciar sesión')
      }
    }
  }
}
</script>