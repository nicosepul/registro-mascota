<template>
  <div class="container mt-4">
    <div class="card shadow p-4">
      <h1 class="text-center mb-4">Sistema de Registro de Mascotas</h1>

      <nav class="nav nav-pills flex-wrap gap-2 mb-4 justify-content-center">
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

      <hr />

      <router-view />
    </div>
  </div>
</template>