import { createApp } from 'vue';
import App from './app/App.vue';
import "tailwindcss/tailwind.css"
import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/pro-solid-svg-icons'
import { fal } from '@fortawesome/pro-light-svg-icons'
import { far } from '@fortawesome/pro-regular-svg-icons'
import { fad } from '@fortawesome/pro-duotone-svg-icons'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(fas, fal, far, fad)

let app = createApp(App);
app.component('font-awesome-icon', FontAwesomeIcon);
app.mount('#app')

window.address = 'http://127.0.0.1:8000';
