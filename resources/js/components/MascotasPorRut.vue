<template>
  <div>
    <h2>Buscar Mascotas por RUT del Dueño</h2>

    <input v-model="rut" type="text" placeholder="Rut del dueño" />
    <button @click="buscarMascotas">Buscar</button>

    <div v-if="mascotas.length > 0" style="margin-top: 20px;">
      <h3>Listado de Mascotas</h3>

      <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre Mascota</th>
            <th>Raza</th>
            <th>Edad</th>
            <th>Dueño</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="mascota in mascotas" :key="mascota.id">
            <td>{{ mascota.id }}</td>
            <td>{{ mascota.nombre }}</td>
            <td>{{ mascota.raza.nombre }}</td>
            <td>{{ mascota.edad }}</td>
            <td>{{ mascota.dueno.nombre }}</td>
          </tr>
        </tbody>
      </table>
    </div>
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
    async buscarMascotas() {
      this.mascotas = []

      const respuesta = await fetch(`/api/mascotas-por-rut/${this.rut}`)
      const data = await respuesta.json()

      if (!respuesta.ok) {
        alert(data.mensaje || 'No se encontraron mascotas')
        return
      }

      this.mascotas = data
    }
  }
}
</script>

<style scoped>
input {
  display: block;
  width: 100%;
  margin-bottom: 10px;
  padding: 8px;
}

button {
  padding: 8px 12px;
  cursor: pointer;
  margin-bottom: 15px;
}
</style>