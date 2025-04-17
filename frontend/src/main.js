import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

const app = createApp(App)

if (import.meta.env.PROD) {
    app.config.devtools = false
    app.config.debug = false
    app.config.warnHandler = () => { } // silencia os avisos também
}

app.use(createPinia())
app.use(router)

app.mount('#app')
