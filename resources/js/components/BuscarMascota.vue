<template>
  <div>
    <h2>Buscar Mascota por RUT y Nombre</h2>

    <input v-model="rut" type="text" placeholder="Rut del dueño" />
    <input v-model="nombre_mascota" type="text" placeholder="Nombre de la mascota" />

    <button @click="buscarMascota">Buscar</button>

    <div v-if="resultado" style="margin-top: 20px; border: 1px solid #ccc; padding: 15px;">
      <h3>Resultado</h3>
      <p><strong>Dueño:</strong> {{ resultado.dueno.nombre }}</p>
      <p><strong>RUT:</strong> {{ resultado.dueno.rut }}</p>
      <p><strong>Dirección:</strong> {{ resultado.dueno.direccion }}</p>
      <p><strong>Mascota:</strong> {{ resultado.nombre }}</p>
      <p><strong>Raza:</strong> {{ resultado.raza.nombre }}</p>
      <p><strong>Edad:</strong> {{ resultado.edad }}</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      rut: '',
      nombre_mascota: '',
      resultado: null
    }
  },
  methods: {
    async buscarMascota() {
      this.resultado = null

      const respuesta = await fetch('/api/buscar-mascota', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          rut: this.rut,
          nombre_mascota: this.nombre_mascota
        })
      })

      const data = await respuesta.json()

      if (!respuesta.ok) {
        alert(data.mensaje || 'No se encontró la mascota')
        return
      }

      this.resultado = data
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
}
</style>