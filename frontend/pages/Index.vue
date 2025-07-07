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
    //await challengeStore.loadChallenge(true) // If we want to ensure the data is always fresh (Handle multi-user?)
    return await challengeStore.loadChallenge();
});

const handleDelete = async (challenge) => {
    try {
        await challengeStore.removeChallenge(challenge);
    } catch (error) {
        console.error('Failed to delete challenge:', error);
    }
};
</script>

<template>
    <h1>CountUp</h1>
    <Stage>
    </Stage>
    </hr>
    <router-link to="/challenge/new">Add New Challenge</router-link>
</template>