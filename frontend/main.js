import { createApp} from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import { createWebHistory, createRouter } from 'vue-router'
import 'bootstrap/dist/css/bootstrap.css'


import Index from './pages/Index.vue';
import Leaderboard from './pages/Leaderboard.vue';
// import ChallengeEdit from './pages/ChallengeEdit.vue';
// import ChallengeCreate from './pages/ChallengeCreate.vue';
import 'bootstrap-icons/font/bootstrap-icons.css'

const routes = [
    { path: '/', component: Index },
    { path: '/leaderboard', component: Leaderboard },
    // { path: '/challenge/edit/:id', component: ChallengeEdit },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

const app = createApp(App);
app.use(createPinia());
app.use(router);
app.mount('#app');