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
</script>

<template>
    <h1>CountUp</h1>
    <Stage 
        :challenge="challengeStore.challenge">
    >
    </Stage>
    </hr>
    <router-link to="/challenge/new">Add New Challenge</router-link>
</template>