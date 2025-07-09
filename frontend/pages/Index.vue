<script setup>
import { onMounted } from 'vue';
import { useChallengeStore } from '../stores/ChallengeStore.js';
import Stage from '../components/Game/Stage.vue';

const challengeStore = useChallengeStore();

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

function handleReset(){
    challengeStore.newChallenge();
}
</script>

<template>
    <div>
        <div class="d-flex justify-content-center">
            <h1>{{ challengeStore.challenge.score }}</h1>
        </div>
        <div>
            <div class="pb-5">
                <Stage 
                    :challenge="challengeStore.challenge"
                    @submitAnswer="handleSubmitAnswer"
                    @complete="handleComplete"
                    @reset="handleReset"
                >
                </Stage>
            </div>
            
            <div class="d-flex justify-content-center">
                <div class="position-fixed pt-0 bottom-0 w-100 bg-white py-3 d-flex justify-content-center">
                    <router-link to="/leaderboard">View Leaderboard</router-link>
                </div>
            </div>
        </div>
    </div>
    
</template>