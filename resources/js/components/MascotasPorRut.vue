<template>
  <div>
    <h2>Gestión de Mascotas por RUT</h2>

    <div class="search-box">
      <input v-model="rut" type="text" placeholder="Ingrese RUT del dueño" @input="formatearRut" @keyup.enter="buscarMascotas" />
      <button @click="buscarMascotas">Consultar</button>
    </div>

    <div v-if="mascotas.length > 0" style="margin-top: 20px;">
      <h3>Mascotas encontradas</h3>
      <table border="1" cellpadding="8" width="100%">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Raza</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="m in mascotas" :key="m.id">
            <td>{{ m.nombre }}</td>
            <td>{{ m.raza.nombre }}</td>
            <td><button @click="resultado = m">Ver Detalle</button></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="resultado" class="detalle-card">
        <h3>Ficha Técnica: {{ resultado.nombre }}</h3>
        <p><strong>Dueño:</strong> {{ resultado.dueno.nombre }}</p>
        <p><strong>RUT:</strong> {{ resultado.dueno.rut }}</p>
        <p><strong>Dirección:</strong> {{ resultado.dueno.direccion }}</p>
        <p><strong>Mascota:</strong> {{ resultado.nombre }}</p>
        <p><strong>Raza:</strong> {{ resultado.raza.nombre }}</p>
        <p><strong>Edad:</strong> {{ resultado.edad }} años</p>
        <button @click="resultado = null">Cerrar Detalle</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      rut: '',
      mascotas: [],
      resultado: null
    }
  },
  methods: {
    formatearRut() {
      let valor = this.rut.replace(/[^0-9kK]/g, '')

      if (valor.length <= 1) {
        this.rut = valor
        return
      }

      let cuerpo = valor.slice(0, -1)
      let dv = valor.slice(-1).toUpperCase()

      cuerpo = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, '.')
      this.rut = cuerpo + '-' + dv
    },

    async buscarMascotas() {
      if (!this.rut.trim()) return alert('Ingrese un RUT')
      
      this.mascotas = [];
      this.resultado = null;

      try {
        const { data } = await axios.get(`/api/mascotas-por-rut/${this.rut}`);

        this.mascotas = data;

        // Si solo hay una mascota, mostrar el detalle automáticamente
        if (this.mascotas.length === 1) {
          this.resultado = this.mascotas[0];
        }

      } catch (error) {
        alert(error.response?.data?.mensaje || 'No se encontraron registros');
      }
    }
  }
}

</script>

<style scoped>
.main-container {
  max-width: 800px;
  margin: 20px auto;
  font-family: sans-serif;
  color: #333;
}

/* Espaciado de títulos y buscador */
h2, h3 { margin: 25px 0 15px; }

.search-box {
  display: flex;
  gap: 10px;
  margin-bottom: 30px;
}

input {
  flex: 1;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

/* Tabla simple y aireada */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

th, td {
  text-align: left;
  padding: 12px 8px;
  border-bottom: 1px solid #eee;
}

/* Ficha de detalle limpia */
.detalle-card {
  margin-top: 30px;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
  line-height: 1.8; /* Da aire entre líneas de texto */
}

.detalle-card h3 {
  margin-top: 0;
  border-bottom: 1px solid #eee;
  padding-bottom: 10px;
}

button {
  padding: 8px 15px;
  cursor: pointer;
  background: #f0f0f0;
  border: 1px solid #ccc;
  border-radius: 4px;
}

button:hover { background: #e0e0e0; }
</style>