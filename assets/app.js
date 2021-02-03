import { createApp } from 'vue';
import App from './app/App.vue';
import "tailwindcss/tailwind.css"
import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(fas)

let app = createApp(App);
app.component('font-awesome-icon', FontAwesomeIcon);
app.mount('#app')
