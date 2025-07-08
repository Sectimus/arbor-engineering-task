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
    challengeStore.completeChallenge(name);
}
</script>

<template>
    <div>
        <div class="d-flex justify-content-center">
            <h1>{{ challengeStore.challenge.score }}</h1>
        </div>
        <div>
            <Stage 
                :challenge="challengeStore.challenge"
                @submitAnswer="handleSubmitAnswer"
                @complete="handleComplete"
            >
            </Stage>
            <div class="d-flex justify-content-center">
                <router-link to="/leaderboard">View Leaderboard</router-link> 
            </div>
        </div>
    </div>
    
</template>