import { createApp } from 'vue'
import App from './App.vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue-next/dist/bootstrap-vue-next.css'
import BootstrapVueNext from 'bootstrap-vue-next'
import router from './router' // Asegúrate de importar tu configuración de router

const app = createApp(App)

app.use(BootstrapVueNext)
app.use(router) // Asegúrate de usar el router

app.mount('#app')
