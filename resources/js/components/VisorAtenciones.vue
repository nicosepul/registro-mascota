<template>
  <div class="container mt-4">
    <h2 class="mb-4">Visor de Atenciones</h2>

    <!-- Buscar por RUT -->
    <div class="card shadow-sm p-4 mb-4">
      <h5 class="mb-3">Buscar mascotas por RUT del dueño</h5>

      <div class="row g-2">
        <div class="col-md-8">
          <input
            v-model="rut"
            type="text"
            class="form-control"
            placeholder="Ingrese RUT del dueño"
          />
        </div>
        <div class="col-md-4">
          <button class="btn btn-primary w-100" @click="buscarMascotas">
            Buscar Mascotas
          </button>
        </div>
      </div>
    </div>

    <!-- Mensaje -->
    <div v-if="mensaje" class="alert alert-info">
      {{ mensaje }}
    </div>

    <!-- Lista de mascotas -->
    <div v-if="mascotas.length > 0" class="card shadow-sm p-4 mb-4">
      <h5 class="mb-3">Seleccione una mascota</h5>

      <div class="list-group">
        <button
          v-for="mascota in mascotas"
          :key="mascota.id"
          class="list-group-item list-group-item-action"
          @click="seleccionarMascota(mascota)"
        >
          <strong>{{ mascota.nombre}}</strong> 
        </button>
      </div>
    </div>

    <!-- Datos de la mascota seleccionada -->
    <div v-if="mascotaSeleccionada" class="card shadow-sm p-4 mb-4">
      <h5 class="mb-3">Mascota Seleccionada</h5>

      <div class="row">
        <div class="col-md-6">
          <p><strong>Id:</strong> {{ mascotaSeleccionada.id}}</p>
          <p><strong>Nombre:</strong> {{ mascotaSeleccionada.nombre}}</p>
        </div>
        <div class="col-md-6">
          <p><strong>Raza:</strong> {{ mascotaSeleccionada.raza.nombre }}</p>
          <p><strong>Edad:</strong> {{ mascotaSeleccionada.edad }}</p>
        </div>
      </div>
    </div>

    <!-- Historial de atenciones -->
    <div v-if="mascotaSeleccionada" class="card shadow-sm p-4 mb-4">
      <h5 class="mb-3">Historial de Atenciones</h5>

      <div v-if="atenciones.length === 0" class="alert alert-warning">
        Esta mascota no tiene atenciones registradas.
      </div>

      <div v-else class="list-group">
        <button
          v-for="atencion in atenciones"
          :key="atencion.id"
          class="list-group-item list-group-item-action"
          @click="seleccionarAtencion(atencion)"
        >
          <div class="d-flex justify-content-between align-items-center">
            <span>
              <strong>{{ formatearFecha(atencion.created_at) }}</strong>
            </span>
            <span class="badge bg-primary">Ver detalle</span>
          </div>
        </button>
      </div>
    </div>

    <!-- Detalle completo de la atención -->
    <div v-if="atencionSeleccionada" class="card shadow-sm p-4 mb-5">
      <h5 class="mb-3">Detalle Completo de la Atención</h5>

      <p><strong>Fecha:</strong> {{ formatearFecha(atencionSeleccionada.created_at) }}</p>
      <p><strong>Motivo:</strong> {{ atencionSeleccionada.motivo_consulta || 'Sin dato' }}</p>
      <p><strong>Diagnóstico:</strong> {{ atencionSeleccionada.diagnostico || 'Sin dato' }}</p>
      <p><strong>Tratamiento:</strong> {{ atencionSeleccionada.tratamiento || 'Sin dato' }}</p>

      <!-- Si tienes más campos, los puedes agregar aquí -->
      <p v-if="atencionSeleccionada.observaciones">
        <strong>Observaciones:</strong> {{ atencionSeleccionada.observaciones }}
      </p>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'VisorAtenciones',
  data() {
    return {
      rut: '',
      mascotas: [],
      mascotaSeleccionada: null,
      atenciones: [],
      atencionSeleccionada: null,
      mensaje: ''
    }
  },
  methods: {
    async buscarMascotas() {
      this.mensaje = ''
      this.mascotas = []
      this.mascotaSeleccionada = null
      this.atenciones = []
      this.atencionSeleccionada = null

      if (!this.rut.trim()) {
        this.mensaje = 'Ingrese un RUT'
        return
      }

      try {
        const { data } = await axios.get(`/api/mascotas-por-rut/${this.rut}`)

        if (!Array.isArray(data) || data.length === 0) {
          this.mensaje = 'No se encontraron mascotas para ese RUT'
          return
        }

        this.mascotas = data
      } catch (error) {
        console.error(error)
        this.mensaje = error.response?.data?.mensaje || 'Error al buscar mascotas'
      }
    },

    async seleccionarMascota(mascota) {
      this.mascotaSeleccionada = mascota
      this.atenciones = []
      this.atencionSeleccionada = null
      this.mensaje = ''

      try {
        const { data } = await axios.get(`/api/atenciones/${mascota.id}`)

        this.atenciones = Array.isArray(data) ? data : []

        if (this.atenciones.length === 0) {
          this.mensaje = 'Esta mascota no tiene atenciones registradas'
        }
      } catch (error) {
        console.error(error)
        this.mensaje = error.response?.data?.mensaje || 'Error al cargar las atenciones'
      }
    },

    seleccionarAtencion(atencion) {
      this.atencionSeleccionada = atencion
    },

    formatearFecha(fecha) {
      if (!fecha) return 'Sin fecha'
      return new Date(fecha).toLocaleString('es-CL')
    }
  }
}
</script>