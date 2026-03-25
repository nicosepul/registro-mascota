<template>
  <div>
    <h2>Mascotas por RUT del Dueño</h2>

    <div>
      <label>RUT del Dueño:</label><br>
      <input v-model="rut" type="text">
    </div>

    <br>
    <button @click="buscarPorRut">Buscar</button>

    <h3 style="margin-top: 20px;">Listado de Mascotas</h3>

    <table v-if="mascotas.length > 0" border="1" cellpadding="8" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre Mascota</th>
          <th>Raza</th>
          <th>Edad</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="mascota in mascotas" :key="mascota.id">
          <td>{{ mascota.id }}</td>
          <td>{{ mascota.nombre }}</td>
          <td>{{ mascota.raza?.nombre || 'Sin raza' }}</td>
          <td>{{ mascota.edad }}</td>
        </tr>
      </tbody>
    </table>

    <p v-else>No se encontraron mascotas para ese RUT.</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      rut: '',
      mascotas: []
    }
  },

  methods: {
    async buscarPorRut() {
      const respuesta = await fetch('/api/mascotas-por-rut?rut=' + this.rut)
      this.mascotas = await respuesta.json()
    }
  }
}
</script>