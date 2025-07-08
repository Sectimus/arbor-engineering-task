<script setup>
import { onMounted, computed } from 'vue';
import { useLeaderboardStore } from '../stores/LeaderboardStore.js';

const leaderboardStore = useLeaderboardStore();

// Load the challenge when the component mounts
onMounted(async () => {
    await leaderboardStore.getChampions();
});

const sortedLeaderboard = computed(() => {
    return leaderboardStore.champions.sort((a, b) => b.score - a.score );
});


function sortedArray() {
    return this.champion.sort((a, b) => a.name - b.name );
}
</script>
<template>
    <div class="container d-flex flex-column align-items-center justify-content-center min-vh-100 pb-4">
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
                <tr v-for="(champion, i) in sortedLeaderboard">
                    <td>{{ i+1 }}</td>
                    <td>{{ champion.name }}</td>
                    <td>{{ champion.score }}</td>
                </tr>
            </tbody>
        </table>
        <div class="position-fixed pt-0 bottom-0 w-100 bg-white py-3 d-flex justify-content-center">
            <router-link to="/">Return to game</router-link> 
        </div>
    </div>
</template>
