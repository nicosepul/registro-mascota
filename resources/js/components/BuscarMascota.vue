<template>
  <div>
    <h2>Buscar Mascota</h2>

    <div>
      <label>RUT del Dueño:</label><br>
      <input v-model="rut" type="text">
    </div>

    <div style="margin-top: 10px;">
      <label>Nombre de la Mascota:</label><br>
      <input v-model="nombreMascota" type="text">
    </div>

    <br>
    <button @click="buscarMascotas">Buscar</button>

    <h3 style="margin-top: 20px;">Resultados de la búsqueda</h3>

    <table v-if="resultados.length > 0" border="1" cellpadding="8" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>RUT Dueño</th>
          <th>Dueño</th>
          <th>Mascota</th>
          <th>Raza</th>
          <th>Edad</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="mascota in resultados" :key="mascota.id">
          <td>{{ mascota.id }}</td>
          <td>{{ mascota.dueno?.rut || 'Sin RUT' }}</td>
          <td>{{ mascota.dueno?.nombre || 'Sin dueño' }}</td>
          <td>{{ mascota.nombre }}</td>
          <td>{{ mascota.raza?.nombre || 'Sin raza' }}</td>
          <td>{{ mascota.edad }}</td>
        </tr>
      </tbody>
    </table>

    <p v-else>No se encontraron resultados.</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      rut: '',
      nombreMascota: '',
      resultados: []
    }
  },

  methods: {
    async buscarMascotas() {
      const params = new URLSearchParams()

      if (this.rut) params.append('rut', this.rut)
      if (this.nombreMascota) params.append('nombre_mascota', this.nombreMascota)

      const respuesta = await fetch('/api/buscar-mascotas?' + params.toString())
      this.resultados = await respuesta.json()
    }
  }
}
</script>