<template>
  <div class="contenedor">
    <h1>Sistema de Registro de Mascotas</h1>

    <nav class="menu">
      <router-link to="/">Registro mascotas</router-link>
      <router-link to="/mascotas-por-rut">Mascotas por dueño</router-link>
      <router-link to="/registrar-atencion">Registrar Atención</router-link>
      <router-link to="/visor-atenciones">Visor de Atenciones</router-link>
      
    </nav>

    <hr />

    <router-view />
  </div>
</template>

<style scoped>
.contenedor {
  max-width: 900px;
  margin: 20px auto;
  padding: 20px;
}

.menu {
  display: flex;
  gap: 15px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.menu a {
  text-decoration: none;
  color: blue;
  font-weight: bold;
}

.menu a.router-link-active {
  color: darkred;
}
</style>