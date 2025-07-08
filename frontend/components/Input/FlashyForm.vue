<script setup>
import { defineModel, defineEmits } from 'vue';
import { useChallengeStore } from '../../stores/ChallengeStore.js';
const challengeStore = useChallengeStore();

const model = defineModel();
defineEmits(['submit']);

function handleClick(){
    challengeStore.newChallenge()
}
</script>

<template>
    <form @submit.prevent="$emit('submit', model)" class="d-flex flex-wrap justify-content-center input-group mb-3">
        <button @click="handleClick" class="btn btn-outline-secondary" type="button" id="btn-new"><i class="bi bi-arrow-clockwise fs-1"></i></button>
        <input 
            v-model="model"
            type="text" 
            class="form-control-lg text-center text-uppercase fs-2" 
            placeholder="Word..." aria-label="Word..." aria-describedby="btn-submit"
        />
        <button class="btn btn-outline-primary" type="submit" id="btn-submit">Guess</button>
    </form>
</template>
