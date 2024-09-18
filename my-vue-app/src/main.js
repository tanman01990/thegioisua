import { createApp } from 'vue';
import { createPinia } from 'pinia';

import App from './App.vue';
import { router } from './router';

// setup fake backend
import { fakeBackend } from './helpers';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap/dist/js/bootstrap.bundle.js'
fakeBackend();

const app = createApp(App);

app.use(createPinia());
app.use(router);

app.mount('#app');