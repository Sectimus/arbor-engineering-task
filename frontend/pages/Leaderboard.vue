<script setup>
import { onMounted } from 'vue';
import { useChallengeStore } from '../stores/ChallengeStore.js';
import { useRouter } from 'vue-router';
import Stage from '../components/Game/Stage.vue';

const challengeStore = useChallengeStore();
const router = useRouter();
console.log('Index component mounted');



// Load the challenge when the component mounts
onMounted(async () => {
    await challengeStore.getChallenge();
});

function handleSubmitAnswer(answer){
    //find out if it was a correct answer
    challengeStore.submitChallengeAnswer(answer);
}

function handleComplete(name){
    debugger;
    challengeStore.completeChallenge(name);
}
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
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>13</td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>10</td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td>Larry</td>
                    <td>7</td>
                </tr>
            </tbody>
        </table>
        <router-link to="/">Return to game</router-link> 
    </div>
</template>
