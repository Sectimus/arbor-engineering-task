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
    <div>
        <div class="d-flex justify-content-between">
            <span>asd</span>
            <h1>CountUp</h1>
            <span>1237</span>
        </div>
        <div cols="1">
            <Stage 
                :challenge="challengeStore.challenge">
            >
            </Stage>
        </div>
    </div>
    <!-- </hr>
    <router-link to="/challenge/new">Add New Challenge</router-link> -->
</template>