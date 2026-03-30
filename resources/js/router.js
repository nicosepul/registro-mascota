import { createRouter, createWebHistory } from 'vue-router'

import App from './components/App.vue'
import Login from './components/Login.vue'
import RegistroMascotas from './components/MascotaApp.vue'
import MascotasPorRut from './components/MascotasPorRut.vue'
import RegistrarAtencion from './components/RegistrarAtencion.vue'
import VisorAtenciones from './components/VisorAtenciones.vue'

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/',
    component: App,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'registro',
        component: RegistroMascotas
      },
      {
        path: 'mascotas-por-rut',
        name: 'mascotasPorRut',
        component: MascotasPorRut
      },
      {
        path: 'registrar-atencion',
        name: 'registrarAtencion',
        component: RegistrarAtencion
      },
      {
        path: 'visor-atenciones',
        name: 'visorAtenciones',
        component: VisorAtenciones
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const usuario = localStorage.getItem('usuario')

  if (to.meta.requiresAuth && !usuario) {
    next('/login')
  } else {
    next()
  }
})

export default router