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
</script>

<template>
    <div>
        <div class="d-flex justify-content-center">
            <h1>{{ challengeStore.challenge.score }}</h1>
        </div>
        <div cols="1">
            <Stage 
                :challenge="challengeStore.challenge"
                @submitAnswer="(answer) => handleSubmitAnswer(answer)"
            >
            </Stage>
        </div>
    </div>
    <!-- </hr>
    <router-link to="/challenge/new">Add New Challenge</router-link> -->
</template>