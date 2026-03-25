<template>
  <div>
    <h2>Ingreso de Atención</h2>

    <div>
      <label>Seleccionar Mascota:</label><br>
      <select v-model="form.mascota_id">
        <option value="">Seleccione una mascota</option>
        <option v-for="mascota in mascotas" :key="mascota.id" :value="mascota.id">
          {{ mascota.nombre }} - {{ mascota.dueno?.rut || 'Sin RUT' }}
        </option>
      </select>
    </div>

    <div style="margin-top: 10px;">
      <label>Motivo del Ingreso:</label><br>
      <input v-model="form.motivo" type="text">
    </div>

    <br>
    <button @click="registrarIngreso">Registrar Ingreso</button>
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

    async registrarIngreso() {
      const respuesta = await fetch('/api/ingresos', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(this.form)
      })

      const data = await respuesta.json()
      alert(data.mensaje || 'Ingreso registrado correctamente')

      this.form = {
        mascota_id: '',
        motivo: 'Se sentía mal'
      }
    }
  }
}
</script>