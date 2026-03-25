<template>
  <div class="contenedor">
    <h1>Sistema de Registro de Mascotas</h1>

    <nav class="menu">
      <router-link to="/">Formulario Principal</router-link>
      <router-link to="/buscar-mascota">Buscar Mascota</router-link>
      <router-link to="/registrar-atencion">Registrar Atención</router-link>
      <router-link to="/mascotas-por-rut">Mascotas por RUT</router-link>
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