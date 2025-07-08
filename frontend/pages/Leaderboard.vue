<script setup>
import { onMounted } from 'vue';
import { useLeaderboardStore } from '../stores/LeaderboardStore.js';

const leaderboardStore = useLeaderboardStore();

// Load the challenge when the component mounts
onMounted(async () => {
    await leaderboardStore.getChampions();
});
</script>
<template>
    <div class="container d-flex flex-column align-items-center justify-content-center min-vh-100">
        <h1 class="mb-4 text-center">Leaderboard</h1>
        <table class="table table-light table-striped border border-primary" style="max-width: 600px; width: 100%;">
            <thead>
                <tr>
                    <th scope="col"><i class="bi bi-trophy"></i></th>
                    <th scope="col">Name</th>
                    <th scope="col">Score</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(champion, i,) in leaderboardStore.champions">
                    <th scope="row">{{ i+1 }}</th>
                    <td>{{ champion.name }}</td>
                    <td>{{ champion.score }}</td>
                </tr>
            </tbody>
        </table>
        <router-link to="/">Return to game</router-link> 
    </div>
</template>
