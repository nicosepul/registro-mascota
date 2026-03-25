import { createRouter, createWebHistory } from 'vue-router'

import MascotaApp from './components/MascotaApp.vue'
import RegistrarAtencion from './components/RegistrarAtencion.vue'
import MascotasPorRut from './components/MascotasPorRut.vue'
import VisorAtenciones from './components/VisorAtenciones.vue'

const routes = [
  {
    path: '/',
    component: MascotaApp
  },
  {
    path: '/registrar-atencion',
    component: RegistrarAtencion
  },
  {
    path: '/mascotas-por-rut',
    component: MascotasPorRut
  },
  {
    path: '/visor-atenciones',
    component: VisorAtenciones
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router