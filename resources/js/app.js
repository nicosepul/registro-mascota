import './bootstrap'
import { createApp } from 'vue'
import AppRoot from './components/AppRoot.vue'
import router from './router'

createApp(AppRoot)
  .use(router)
  .mount('#app')