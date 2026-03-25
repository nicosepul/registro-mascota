import { createRouter, createWebHistory } from 'vue-router'

import MascotaApp from './components/MascotaApp.vue'
import BuscarMascota from './components/BuscarMascota.vue'
import RegistrarAtencion from './components/RegistrarAtencion.vue'
import MascotasPorRut from './components/MascotasPorRut.vue'

const routes = [
  {
    path: '/',
    component: MascotaApp
  },
  {
    path: '/buscar-mascota',
    component: BuscarMascota
  },
  {
    path: '/registrar-atencion',
    component: RegistrarAtencion
  },
  {
    path: '/mascotas-por-rut',
    component: MascotasPorRut
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router