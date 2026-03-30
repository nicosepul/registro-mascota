<template>
  <div class="container mt-4">
    <div class="card shadow p-4">
      <h1 class="text-center mb-4">Sistema de Registro de Mascotas</h1>

      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <nav class="nav nav-pills flex-wrap gap-2">
          <router-link to="/" class="btn btn-outline-primary">
            Registro mascotas
          </router-link>

          <router-link to="/mascotas-por-rut" class="btn btn-outline-success">
            Mascotas por dueño
          </router-link>

          <router-link to="/registrar-atencion" class="btn btn-outline-warning">
            Registrar Atención
          </router-link>

          <router-link to="/visor-atenciones" class="btn btn-outline-danger">
            Visor de Atenciones
          </router-link>
        </nav>

        <button @click="logout" class="btn btn-dark">
          Cerrar sesión
        </button>
      </div>

      <hr />

      <router-view />
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  methods: {
    async logout() {
      try {
        await axios.post('/api/logout')
      } catch (error) {
        // aunque falle, igual cerramos en frontend
      }

      localStorage.removeItem('usuario')
      this.$router.push('/login')
    }
  }
}
</script>