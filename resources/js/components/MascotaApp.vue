<template>
  <div class="container mt-5">
    <h1 class="mb-4">Registro de Mascotas</h1>

    <div v-if="vistaActual === 'formulario'">
      <form @submit.prevent="guardarMascota" class="card shadow p-4 mb-5">
      <h5 class="mb-3">Datos del Dueño</h5>
      
      <div class="mb-3">
        <input v-model="form.rut" type="text" class="form-control" @input="onRutInput" @blur="buscarDuenoPorRut" placeholder="RUT">
        <small v-if="errores.rut" class="text-danger">{{ errores.rut }}</small>
        <small v-else-if="mensajeRut" class="text-success">{{ mensajeRut }}</small>
      </div>

      <input v-model="form.nombre_dueno" type="text" placeholder="Nombre" class="form-control mb-3" :disabled="camposDuenoBloqueados">
      <input v-model="form.apellido_dueno" type="text" placeholder="Apellido" class="form-control mb-3" :disabled="camposDuenoBloqueados">
      <div class="mb-3">
        <input
          type="text"
          v-model="form.telefono"
          class="form-control"
          @blur="validarTelefono"
          :disabled="camposDuenoBloqueados"
          placeholder="Telefono"
        >
        <small v-if="errores.telefono" class="text-danger">
          {{ errores.telefono }}
        </small>
      </div>
      <input v-model="form.direccion" type="text" placeholder="Dirección" class="form-control mb-4" :disabled="camposDuenoBloqueados">

      <h5 class="mb-3">Datos de la Mascota</h5>

      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <label class="form-label">Nombre mascota</label>
          <input v-model="form.nombre_mascota" type="text" placeholder="Nombre mascota" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Especie</label>
          <select v-model="form.especie_id" class="form-select" @change="onEspecieChange">
            <option value="">Seleccione especie</option>
            <option v-for="especie in especies" :key="especie.id" :value="especie.id">{{ especie.nombre }}</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Raza</label>
          <select v-model="form.raza_id" class="form-select">
            <option value="">Seleccione una raza</option>
            <option v-for="raza in razas" :key="raza.id" :value="raza.id">{{ raza.nombre }}</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Sexo</label>
          <select v-model="form.sexo" class="form-select">
            <option value="">Seleccione sexo</option>
            <option value="Macho">Macho</option>
            <option value="Hembra">Hembra</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Edad</label>
          <input v-model="form.edad" type="number" min="0" placeholder="Edad" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Fecha de nacimiento</label>
          <input v-model="form.fecha_nacimiento" type="date" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Peso (kg)</label>
          <input v-model.number="form.peso" type="number" step="0.01" min="0" placeholder="Peso" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Color</label>
          <input v-model="form.color" type="text" placeholder="Color" class="form-control">
        </div>
        <div class="col-12">
          <label class="form-label">Procedencia</label>
          <input v-model="form.procedencia" type="text" placeholder="Procedencia" class="form-control">
        </div>
        <div class="col-12">
          <label class="form-label">Señales particulares</label>
          <textarea v-model="form.senales_particulares" placeholder="Señales particulares (opcional)" class="form-control" rows="3"></textarea>
        </div>
      </div>

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
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
            <div>
              Mostrar
              <select v-model.number="perPage" class="form-select form-select-sm d-inline-block" style="width: auto;">
                <option v-for="op in opcionesPagina" :key="op" :value="op">{{ op }}</option>
              </select>
              por página
            </div>
            <div class="d-flex align-items-center gap-2">
              <input
                v-model="filtroRut"
                type="text"
                class="form-control form-control-sm"
                style="width: 220px;"
                placeholder="Buscar por RUT"
                @input="paginaActual = 1"
              >
              <div>Mostrando {{ desde }} - {{ hasta }} de {{ mascotasFiltradas.length }}</div>
            </div>
          </div>
          <table class="table table-hover align-middle mb-0 d-none d-md-table">
            <thead class="table-light">
              <tr>
                <th>RUT</th>
                <th>Dueño</th>
                <th>Mascota</th>
                <th class="text-nowrap">Especie</th>
                <th class="text-nowrap">Raza</th>
                <th>Edad</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="mascota in mascotasPag" :key="mascota.id">
                <td class="text-nowrap">{{ formatearRutTabla(mascota.dueno.rut) }}</td>
                <td>{{ mascota.dueno.nombre }} {{ mascota.dueno.apellido }}</td>
                <td>{{ mascota.nombre }}</td>
                <td class="col-mini"><span>{{ mascota.especie?.nombre || '-' }}</span></td>
                <td class="col-mini"><span>{{ mascota.raza?.nombre || '-' }}</span></td>
                <td class="text-nowrap">{{ mascota.edad }}</td>
                <td class="text-nowrap">
                  <button @click="editarMascota(mascota)" class="btn btn-sm btn-warning me-2">Editar</button>
                  <button @click="eliminarMascota(mascota.id)" class="btn btn-sm btn-danger">Eliminar</button>
                </td>
              </tr>
              <tr v-if="mascotasPag.length === 0">
                <td colspan="7" class="text-center text-muted py-3">No hay mascotas para el filtro ingresado</td>
              </tr>
            </tbody>
          </table>

          <div class="d-md-none p-2">
            <div v-if="mascotasPag.length === 0" class="text-center text-muted py-3">
              No hay mascotas para el filtro ingresado
            </div>

            <div v-for="mascota in mascotasPag" :key="`mobile-${mascota.id}`" class="card mb-2 shadow-sm">
              <div class="card-body p-3">
                <div class="d-flex justify-content-between mb-2">
                  <strong>{{ mascota.nombre }}</strong>
                  <span class="badge text-bg-light">{{ mascota.especie?.nombre || '-' }}</span>
                </div>
                <div class="small"><strong>RUT:</strong> {{ formatearRutTabla(mascota.dueno.rut) }}</div>
                <div class="small"><strong>Dueño:</strong> {{ mascota.dueno.nombre }} {{ mascota.dueno.apellido }}</div>
                <div class="small"><strong>Especie:</strong> {{ mascota.especie?.nombre || '-' }}</div>
                <div class="small"><strong>Raza:</strong> {{ mascota.raza?.nombre || '-' }}</div>
                <div class="small mb-2"><strong>Edad:</strong> {{ mascota.edad }}</div>
                <div class="d-flex gap-2">
                  <button @click="editarMascota(mascota)" class="btn btn-sm btn-warning flex-fill">Editar</button>
                  <button @click="eliminarMascota(mascota.id)" class="btn btn-sm btn-danger flex-fill">Eliminar</button>
                </div>
              </div>
            </div>
          </div>

          <nav class="d-flex justify-content-center my-2" v-if="totalPaginas > 1">
            <ul class="pagination pagination-sm flex-wrap justify-content-center mb-0">
              <li class="page-item" :class="{ disabled: paginaActual === 1 }">
                <button class="page-link" @click="cambiarPagina(paginaActual - 1)">Anterior</button>
              </li>
              <li class="page-item" :class="{ active: paginaActual === n, disabled: n === '...' }" v-for="n in paginasVisibles" :key="`pag-${n}`">
                <button class="page-link" @click="n !== '...' && cambiarPagina(n)">{{ n }}</button>
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

  </div>
</template>

<script>
import axios from 'axios'

export default {

  data() {
    return {
      vistaActual: 'formulario',
      mascotas: [],
      razas: [],
      especies: [],
      editando: false,
      mascotaId: null,
      form: this.formularioVacio(),
      errores: { 
        rut: '',
        telefono: ''
      },
      mensajeRut: '',
      duenoRegistrado: false,
      rutCheckTimer: null,
      filtroRut: '',
      perPage: 10,
      paginaActual: 1,
      opcionesPagina: [5, 10, 20, 50]
    }
  },

  created() {
    this.obtenerMascotas()
    this.obtenerRazas()
    this.obtenerEspecies()
  },

  beforeUnmount() {
    if (this.rutCheckTimer) {
      clearTimeout(this.rutCheckTimer)
    }
  },

  methods: {
    formularioVacio() {
      return {
        rut: '',
        nombre_dueno: '',
        apellido_dueno: '',
        telefono: '',
        direccion: '',
        nombre_mascota: '',
        especie_id: '',
        sexo: '',
        fecha_nacimiento: '',
        peso: '',
        color: '',
        procedencia: '',
        raza_id: '',
        edad: '',
        senales_particulares: ''
      }
    },

    limpiarDatosDueno() {
      this.form.nombre_dueno = ''
      this.form.apellido_dueno = ''
      this.form.telefono = ''
      this.form.direccion = ''
    },

    obtenerMensajeError(error, fallback = 'Error inesperado') {
      return error?.response?.data?.mensaje || fallback
    },

    formatearRut() {
      let valor = this.form.rut.replace(/[^0-9kK]/g, '')

      if (valor.length > 9) {
        valor = valor.slice(0, 9)
        this.errores.rut = 'Solo se permiten 8 numeros y 1 digito verificador'
      }

      if (valor.length <= 1) {
        this.form.rut = valor
        return
      }

      const cuerpo = valor.slice(0, -1).replace(/\B(?=(\d{3})+(?!\d))/g, '.')
      const dv = valor.slice(-1).toUpperCase()

      this.form.rut = `${cuerpo}-${dv}`
    },

    onRutInput() {
      this.formatearRut()
      this.mensajeRut = ''

      if (this.errores.rut !== 'Solo se permiten 8 numeros y 1 digito verificador') {
        this.errores.rut = ''
      }

      if (this.editando) return

      // limpiar datos si antes había dueño encontrado
      if (this.duenoRegistrado) {
        this.duenoRegistrado = false
        this.limpiarDatosDueno()
      }

      const rutNormalizado = this.normalizarRut(this.form.rut)

      clearTimeout(this.rutCheckTimer)

      if (rutNormalizado.length < 9) return

      if (!this.validarRut(this.form.rut)) {
        this.errores.rut = 'El RUT ingresado no es valido'
        return
      }

      this.rutCheckTimer = setTimeout(() => {
        this.buscarDuenoPorRut()
      }, 250)
    },

    normalizarRut(rut) {
      return (rut || '').replace(/[.-]/g, '').trim().toUpperCase()
    },

    formatearRutTabla(rut) {
      const rutNormalizado = this.normalizarRut(rut)

      if (rutNormalizado.length < 2) return rut || ''

      const cuerpo = rutNormalizado.slice(0, -1)
      const dv = rutNormalizado.slice(-1)
      const cuerpoFormateado = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, '.')

      return `${cuerpoFormateado}-${dv}`
    },

    validarRut(rut) {
      if (!/^[0-9.]+-[0-9Kk]$/.test(rut)) return false

      const [cuerpoFormateado, dv] = rut.split('-')
      const cuerpo = cuerpoFormateado.replace(/\./g, '')
      let suma = 0
      let multiplo = 2

      for (let i = cuerpo.length - 1; i >= 0; i--) {
        suma += Number(cuerpo[i]) * multiplo
        multiplo = multiplo === 7 ? 2 : multiplo + 1
      }

      let esperado = 11 - (suma % 11)
      esperado = esperado === 11 ? '0' : esperado === 10 ? 'K' : esperado.toString()

      return dv.toUpperCase() === esperado
    },

    validarTelefono() {
      this.errores.telefono = ''

      if (!this.form.telefono) {
        this.errores.telefono = 'El teléfono es obligatorio'
        return false
      }

      const telefonoLimpio = this.form.telefono.replace(/[\s-]/g, '')

      if (!/^(\+56|56)?9\d{8}$/.test(telefonoLimpio)) {
        this.errores.telefono = 'Formato válido: +56 9 1234 5678 o 912345678'
        return false
      }

      return true
    },

    async buscarDuenoPorRut() {
      this.mensajeRut = ''
      this.errores.rut = ''

      const limpiarCamposDueno = () => {
        this.form.nombre_dueno = ''
        this.form.apellido_dueno = ''
        this.form.telefono = ''
        this.form.direccion = ''
      }

      if (!this.form.rut.trim()) {
        this.errores.rut = 'El RUT es obligatorio'
        this.duenoRegistrado = false
        limpiarCamposDueno()
        return
      }

      if (this.editando) {
        this.duenoRegistrado = false
        return
      }

      if (!this.validarRut(this.form.rut)) {
        this.errores.rut = 'El RUT ingresado no es valido'
        this.duenoRegistrado = false
        limpiarCamposDueno()
        return
      }

      try {
        const { data } = await axios.get(`/api/duenos/existe-rut/${encodeURIComponent(this.form.rut)}`)

        if (!data?.existe || !data?.dueno) {
          this.duenoRegistrado = false
          limpiarCamposDueno()
          this.mensajeRut = 'RUT nuevo. Complete datos del dueno para registrarlo.'
          return
        }

        this.form.nombre_dueno = data.dueno.nombre || ''
        this.form.apellido_dueno = data.dueno.apellido || ''
        this.form.telefono = data.dueno.telefono || ''
        this.form.direccion = data.dueno.direccion || ''

        this.duenoRegistrado = true
        this.mensajeRut = 'Usuario ya registrado. Campos del dueno bloqueados.'
      } catch (error) {
        this.duenoRegistrado = false
        limpiarCamposDueno()
        this.errores.rut = error.response?.data?.mensaje || 'Error al validar RUT'
      }
    },

    async obtenerMascotas() {
      try {
        const { data } = await axios.get('/api/mascotas')
        this.mascotas = data
      } catch (error) {
        console.error('Error:', error)
        alert(this.obtenerMensajeError(error, 'Error al cargar mascotas'))
      }
    },

    async obtenerRazas() {
      const especieId = this.form.especie_id

      try {
        const url = especieId ? `/api/razas?especie_id=${especieId}` : '/api/razas'
        const { data } = await axios.get(url)
        this.razas = data
      } catch (error) {
        console.error('Error:', error)
        alert(this.obtenerMensajeError(error, 'Error al cargar razas'))
      }
    },

    async onEspecieChange() {
      this.form.raza_id = ''
      await this.obtenerRazas()
    },

    async obtenerEspecies() {
      try {
        const { data } = await axios.get('/api/especies')
        this.especies = data
      } catch (error) {
        console.error('Error:', error)
        alert(this.obtenerMensajeError(error, 'Error al cargar especies'))
      }
    },

    async guardarMascota() {
      if (!this.validarTelefono()) return alert('Teléfono inválido')

      const campos = [
        this.form.rut,
        this.form.nombre_dueno,
        this.form.apellido_dueno,
        this.form.telefono,
        this.form.direccion,
        this.form.nombre_mascota,
        this.form.especie_id,
        this.form.sexo,
        this.form.fecha_nacimiento,
        this.form.peso,
        this.form.color,
        this.form.procedencia,
        this.form.raza_id,
        this.form.edad
      ]

      if (campos.some(c => c === null || c === undefined || c === '')) {
        return alert('Complete todos los campos')
      }

      const url = this.editando
        ? `/api/mascotas/${this.mascotaId}`
        : '/api/mascotas'

      const method = this.editando ? 'put' : 'post'

      try {
        const res = await axios({
          method,
          url,
          data: this.form
        })

        alert(res.data.mensaje || 'Guardado correctamente')
        this.resetear()
        await this.obtenerMascotas()

      } catch (e) {
        console.error(e)
        alert('Error: ' + this.obtenerMensajeError(e, 'Desconocido'))
      }
    },

    editarMascota(m) {
      this.editando = true
      this.duenoRegistrado = false
      this.mensajeRut = ''
      this.mascotaId = m.id
      this.form = {
        rut: m.dueno.rut,
        nombre_dueno: m.dueno.nombre,
        apellido_dueno: m.dueno.apellido || '',
        telefono: m.dueno.telefono || '',
        direccion: m.dueno.direccion,
        nombre_mascota: m.nombre,
        especie_id: m.especie_id || '',
        sexo: m.sexo || '',
        fecha_nacimiento: m.fecha_nacimiento || '',
        peso: m.peso || '',
        color: m.color || '',
        procedencia: m.procedencia || '',
        raza_id: m.raza_id,
        edad: m.edad,
        senales_particulares: m.senales_particulares || ''
      }

      this.obtenerRazas()
    },

    async eliminarMascota(id) {
      if (!confirm('¿Eliminar mascota?')) return

      try {
        const { data } = await axios.delete(`/api/mascotas/${id}`)
        alert(data.mensaje)
        await this.obtenerMascotas()
      } catch (error) {
        console.error('Error:', error)
        alert(this.obtenerMensajeError(error, 'Error al eliminar'))
      }
    },

    resetear() {
      this.editando = false
      this.mascotaId = null
      this.errores.rut = ''
      this.errores.telefono = ''
      this.mensajeRut = ''
      this.duenoRegistrado = false
      clearTimeout(this.rutCheckTimer)
      this.form = this.formularioVacio()
    },

    cambiarPagina(nuevaPagina) {
      if (nuevaPagina < 1 || nuevaPagina > this.totalPaginas) return
      this.paginaActual = nuevaPagina
    }
  },
  computed: {
    camposDuenoBloqueados() {
      return this.duenoRegistrado && !this.editando
    },
    mascotasFiltradas() {
      const filtro = this.normalizarRut(this.filtroRut)

      if (!filtro) {
        return this.mascotas
      }

      return this.mascotas.filter(mascota => {
        const rutDueno = this.normalizarRut(mascota?.dueno?.rut)
        return rutDueno.includes(filtro)
      })
    },
    totalPaginas() {
      return Math.ceil(this.mascotasFiltradas.length / this.perPage) || 1
    },
    paginasVisibles() {
      const total = this.totalPaginas
      const actual = this.paginaActual

      if (total <= 7) {
        return Array.from({ length: total }, (_, i) => i + 1)
      }

      if (actual <= 4) {
        return [1, 2, 3, 4, 5, '...', total]
      }

      if (actual >= total - 3) {
        return [1, '...', total - 4, total - 3, total - 2, total - 1, total]
      }

      return [1, '...', actual - 1, actual, actual + 1, '...', total]
    },
    mascotasPag() {
      const inicio = (this.paginaActual - 1) * this.perPage
      const fin = inicio + this.perPage
      return this.mascotasFiltradas.slice(inicio, fin)
    },
    desde() {
      if (this.mascotasFiltradas.length === 0) return 0
      return (this.paginaActual - 1) * this.perPage + 1
    },
    hasta() {
      return Math.min(this.paginaActual * this.perPage, this.mascotasFiltradas.length)
    }
  }
}
</script>

<style scoped>
.col-mini span {
  display: inline-block;
  max-width: 120px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-size: 0.88rem;
}
</style>