<template>
  <div>
    <h2>Registrar Atención</h2>

    <label>Seleccione Mascota:</label>
    <select v-model="form.mascota_id">
      <option value="">Seleccione una mascota</option>
      <option v-for="mascota in mascotas" :key="mascota.id" :value="mascota.id">
        {{ mascota.nombre }} - {{ mascota.dueno.nombre }} ({{ mascota.dueno.rut }})
      </option>
    </select>

    <label>Motivo:</label>
    <input v-model="form.motivo" type="text" placeholder="Ej: Se sentía mal" />

    <button @click="registrarAtencion">Guardar Atención</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      mascotas: [],
      form: {
        mascota_id: '',
        motivo: 'Se sentía mal'
      }
    }
  },
  mounted() {
    this.obtenerMascotas()
  },
  methods: {
    async obtenerMascotas() {
      const respuesta = await fetch('/api/mascotas')
      this.mascotas = await respuesta.json()
    },

    async registrarAtencion() {
      const respuesta = await fetch('/api/registrar-atencion', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(this.form)
      })

      const data = await respuesta.json()

      if (!respuesta.ok) {
        alert('Error al registrar atención')
        console.log(data)
        return
      }

      alert(data.mensaje)

      this.form = {
        mascota_id: '',
        motivo: 'Se sentía mal'
      }
    }
  }
}
</script>

<style scoped>
input, select {
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