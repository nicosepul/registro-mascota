<template>
  <div>
    <h2>Registrar Atención Médica</h2>

    <label>Mascota:</label>
    <select v-model="form.mascota_id">
      <option value="">Seleccione una mascota</option>
      <option v-for="mascota in mascotas" :key="mascota.id" :value="mascota.id">
        {{ mascota.nombre }} - {{ mascota.dueno.nombre }} ({{ mascota.dueno.rut }})
      </option>
    </select>

    <label>Fecha de atención:</label>
    <input v-model="form.fecha_atencion" type="date" />

    <label>Motivo de consulta:</label>
    <input v-model="form.motivo_consulta" type="text" placeholder="Ej: Decaimiento, vómitos, dolor" />

    <label>Síntomas:</label>
    <textarea v-model="form.sintomas" placeholder="Ej: Fiebre, vómitos, pérdida de apetito"></textarea>

    <label>Diagnóstico:</label>
    <textarea v-model="form.diagnostico" placeholder="Ej: Infección estomacal"></textarea>

    <label>Tratamiento:</label>
    <textarea v-model="form.tratamiento" placeholder="Ej: Antibiótico y reposo"></textarea>

    <label>Observaciones:</label>
    <textarea v-model="form.observaciones" placeholder="Ej: Control en 3 días"></textarea>

    <div style="margin: 10px 0;">
        <label style="display: flex; align-items: center; gap: 3px; font-weight: bold; margin: 0;">
            <input v-model="form.atendido" type="checkbox" style="width: auto; margin: 0; padding: 0;" />
            Atención completada
        </label>
    </div>

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
        fecha_atencion: '',
        motivo_consulta: '',
        sintomas: '',
        diagnostico: '',
        tratamiento: '',
        observaciones: '',
        atendido: false
      }
    }
  },
  mounted() {
    this.obtenerMascotas()
    this.form.fecha_atencion = this.fechaActual()
  },
  methods: {
    fechaActual() {
      const hoy = new Date()
      return hoy.toISOString().split('T')[0]
    },

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
        alert('Error al registrar la atención')
        console.log(data)
        return
      }

      alert(data.mensaje)

      this.form = {
        mascota_id: '',
        fecha_atencion: this.fechaActual(),
        motivo_consulta: '',
        sintomas: '',
        diagnostico: '',
        tratamiento: '',
        observaciones: '',
        atendido: false
      }
    }
  }
}
</script>

<style scoped>
label {
  display: block;
  margin-top: 10px;
  font-weight: bold;
}

input, select, textarea {
  display: block;
  width: 100%;
  margin-bottom: 10px;
  padding: 8px;
}

textarea {
  min-height: 80px;
  resize: vertical;
}

button {
  padding: 10px 15px;
  cursor: pointer;
  margin-top: 10px;
}
</style>