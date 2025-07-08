import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import { createWebHistory, createRouter } from 'vue-router'
// import 'simpledotcss/simple.min.css'; TODO maybe??

import Index from './pages/Index.vue';
import ChallengeEdit from './pages/ChallengeEdit.vue';
import ChallengeCreate from './pages/ChallengeCreate.vue';

const routes = [
    { path: '/', component: Index },
    { path: '/challenge/new', component: ChallengeCreate },
    { path: '/challenge/edit/:id', component: ChallengeEdit },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

const app = createApp(App);
app.use(createPinia());
app.use(router);
app.mount('#app');