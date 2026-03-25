<template>
  <div class="container mt-5">
    <h1 class="mb-4">Registro de Mascotas</h1>

    <div v-if="vistaActual === 'formulario'">
      <form @submit.prevent="guardarMascota" class="card shadow p-4 mb-5">
      <h5 class="mb-3">Datos del Dueño</h5>
      
      <div class="mb-3">
        <input v-model="form.rut" type="text" class="form-control" @blur="validarRut" placeholder="RUT">
        <small v-if="errores.rut" class="text-danger">{{ errores.rut }}</small>
      </div>

      <input v-model="form.nombre_dueno" type="text" placeholder="Nombre" class="form-control mb-3">
      <input v-model="form.apellido_dueno" type="text" placeholder="Apellido" class="form-control mb-3">
      <input v-model="form.telefono" type="text" placeholder="Teléfono" class="form-control mb-3">
      <input v-model="form.direccion" type="text" placeholder="Dirección" class="form-control mb-4">

      <h5 class="mb-3">Datos de la Mascota</h5>
      
      <input v-model="form.nombre_mascota" type="text" placeholder="Nombre mascota" class="form-control mb-3">
      <select v-model="form.raza_id" class="form-select mb-3">
        <option value="">Seleccione una raza</option>
        <option v-for="raza in razas" :key="raza.id" :value="raza.id">{{ raza.nombre }}</option>
      </select>
      <input v-model="form.edad" type="number" placeholder="Edad" class="form-control mb-4">

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">{{ editando ? 'Actualizar' : 'Guardar' }}</button>
        <button v-if="editando" type="button" @click="resetear" class="btn btn-secondary">Cancelar</button>
      </div>
    </form>

    <div class="card shadow">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Listado de Mascotas</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
              Mostrar
              <select v-model.number="perPage" class="form-select form-select-sm d-inline-block" style="width: auto;">
                <option v-for="op in opcionesPagina" :key="op" :value="op">{{ op }}</option>
              </select>
              por página
            </div>
            <div>Mostrando {{ desde }} - {{ hasta }} de {{ mascotas.length }}</div>
          </div>
          <table class="table mbm-0">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>RUT</th>
                <th>Nombre</th>
                <th>Mascota</th>
                <th>Raza</th>
                <th>Edad</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="mascota in mascotasPag" :key="mascota.id">
                <td>{{ mascota.id }}</td>
                <td>{{ mascota.dueno.rut }}</td>
                <td>{{ mascota.dueno.nombre }} {{ mascota.dueno.apellido }}</td>
                <td>{{ mascota.nombre }}</td>
                <td>{{ mascota.raza.nombre }}</td>
                <td>{{ mascota.edad }}</td>
                <td>
                  <button @click="editarMascota(mascota)" class="btn btn-sm btn-warning me-2">Editar</button>
                  <button @click="eliminarMascota(mascota.id)" class="btn btn-sm btn-danger">Eliminar</button>
                </td>
              </tr>
            </tbody>
          </table>
          <nav class="d-flex justify-content-center my-2" v-if="totalPaginas > 1">
            <ul class="pagination mb-0">
              <li class="page-item" :class="{ disabled: paginaActual === 1 }">
                <button class="page-link" @click="cambiarPagina(paginaActual - 1)">Anterior</button>
              </li>
              <li class="page-item" :class="{ active: paginaActual === n }" v-for="n in totalPaginas" :key="n">
                <button class="page-link" @click="cambiarPagina(n)">{{ n }}</button>
              </li>
              <li class="page-item" :class="{ disabled: paginaActual === totalPaginas }">
                <button class="page-link" @click="cambiarPagina(paginaActual + 1)">Siguiente</button>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
    </div>

    <BuscarMascota v-if="vistaActual === 'buscar'" />
    <RegistrarAtencion v-if="vistaActual === 'ingreso'" />
    <MascotasPorRut v-if="vistaActual === 'listarPorRut'" />
  </div>
</template>

<script>
import BuscarMascota from './BuscarMascota.vue'
import RegistrarAtencion from './RegistrarAtencion.vue'
import MascotasPorRut from './MascotasPorRut.vue'

export default {
  components: {
    BuscarMascota,
    RegistrarAtencion,
    MascotasPorRut
  },
  data() {
    return {
      vistaActual: 'formulario',
      mascotas: [],
      razas: [],
      editando: false,
      mascotaId: null,
      errores: { rut: '' },
      form: {
        rut: '',
        nombre_dueno: '',
        apellido_dueno: '',
        telefono: '',
        direccion: '',
        nombre_mascota: '',
        raza_id: '',
        edad: ''
      },
      perPage: 10,
      paginaActual: 1,
      opcionesPagina: [5, 10, 20, 50]
    }
  },

  created() {
    this.obtenerMascotas()
    this.obtenerRazas()
  },

  methods: {
    formatearRut() {
    let valor = this.form.rut.replace(/[^0-9kK]/g, '')

    if (valor.length <= 1) {
      this.form.rut = valor
      return
    }

    let cuerpo = valor.slice(0, -1)
    let dv = valor.slice(-1).toUpperCase()

    cuerpo = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, '.')
    this.form.rut = cuerpo + '-' + dv
  },

    validarRut() {
      this.errores.rut = ''

      if (!this.form.rut) {
        this.errores.rut = 'El RUT es obligatorio'
        return false
      }

      if (!/^[0-9.]+-[0-9kK]{1}$/.test(this.form.rut)) {
        this.errores.rut = 'Formato: XX.XXX.XXX-K'
        return false
      }

      let tmp = this.form.rut.split('-')
      let dv = tmp[1].toUpperCase()
      let cuerpo = tmp[0].replace(/\./g, '')

      let res = 0
      let multiplicador = 2

      for (let i = cuerpo.length - 1; i >= 0; i--) {
        res += parseInt(cuerpo.charAt(i)) * multiplicador
        multiplicador = multiplicador === 7 ? 2 : multiplicador + 1
      }

      let dvr = 11 - (res % 11)
      dvr = dvr === 11 ? '0' : dvr === 10 ? 'K' : dvr.toString()

      if (dv !== dvr) {
        this.errores.rut = 'El RUT ingresado no es válido'
        return false
      }

      return true
    },

    async obtenerMascotas() {
      try {
        this.mascotas = await (await fetch('/api/mascotas')).json()
      } catch (e) {
        console.error('Error:', e)
        alert('Error al cargar mascotas')
      }
    },

    async obtenerRazas() {
      try {
        this.razas = await (await fetch('/api/razas')).json()
      } catch (e) {
        console.error('Error:', e)
        alert('Error al cargar razas')
      }
    },

    async guardarMascota() {
      if (!this.validarRut()) return alert('RUT inválido')
      
      const campos = [this.form.nombre_dueno, this.form.apellido_dueno, this.form.telefono, 
                      this.form.direccion, this.form.nombre_mascota, this.form.raza_id, this.form.edad]
      if (campos.some(c => !c)) return alert('Complete todos los campos')

      try {
        const url = this.editando ? `/api/mascotas/${this.mascotaId}` : '/api/mascotas'
        const res = await fetch(url, {
          method: this.editando ? 'PUT' : 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.form)
        })

        const data = await res.json()
        if (!res.ok) return alert('Error: ' + (data.message || 'Desconocido'))

        alert(data.mensaje)
        this.resetear()
        this.obtenerMascotas()
      } catch (e) {
        console.error(e)
        alert('Error al guardar')
      }
    },

    editarMascota(m) {
      this.editando = true
      this.mascotaId = m.id
      this.form = {
        rut: m.dueno.rut,
        nombre_dueno: m.dueno.nombre,
        apellido_dueno: m.dueno.apellido || '',
        telefono: m.dueno.telefono || '',
        direccion: m.dueno.direccion,
        nombre_mascota: m.nombre,
        raza_id: m.raza_id,
        edad: m.edad
      }
    },

    async eliminarMascota(id) {
      if (!confirm('¿Eliminar mascota?')) return
      try {
        const data = await (await fetch(`/api/mascotas/${id}`, { method: 'DELETE' })).json()
        alert(data.mensaje)
        this.obtenerMascotas()
      } catch (e) {
        console.error(e)
        alert('Error al eliminar')
      }
    },

    resetear() {
      this.editando = false
      this.mascotaId = null
      this.errores.rut = ''
      this.form = {
        rut: '',
        nombre_dueno: '',
        apellido_dueno: '',
        telefono: '',
        direccion: '',
        nombre_mascota: '',
        raza_id: '',
        edad: ''
      }
    },

    cambiarPagina(nuevaPagina) {
      if (nuevaPagina < 1 || nuevaPagina > this.totalPaginas) return
      this.paginaActual = nuevaPagina
    }
  },
  computed: {
    totalPaginas() {
      return Math.ceil(this.mascotas.length / this.perPage) || 1
    },
    mascotasPag() {
      const inicio = (this.paginaActual - 1) * this.perPage
      const fin = inicio + this.perPage
      return this.mascotas.slice(inicio, fin)
    },
    desde() {
      if (this.mascotas.length === 0) return 0
      return (this.paginaActual - 1) * this.perPage + 1
    },
    hasta() {
      return Math.min(this.paginaActual * this.perPage, this.mascotas.length)
    }
  }
}
</script>