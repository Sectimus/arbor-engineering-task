import { ref } from 'vue';
import { defineStore } from 'pinia';
import ChallengeApi from '../services/ChallengeApi';
import { createChallenge } from '../models/Challenge';

export const useChallengeStore = defineStore('challenge', () => {
    const challenge = ref(createChallenge());
    const complete = ref(false);
    const solutions = ref([]);
    const isLoading = ref(false);
    const error = ref(null);
    const loadedFromApi = ref(false);

    async function newChallenge() {
        try {
            isLoading.value = true;
            const apiData = await ChallengeApi.newChallenge();
            challenge.value = createChallenge(apiData).value;
        } catch (err) {
            error.value = err.message;
        } finally {
            isLoading.value = false;
            loadedFromApi.value = true;
            complete.value = false;
        }
    }

    async function getChallenge() {
        await loadChallenge(); // If our challenge is not loaded yet
        if(!challenge.value){
            throw "Problem retrieving challenge"
        }
    }

    async function submitChallengeAnswer(answer) {
        try{
            challenge.value = await ChallengeApi.submitChallengeAnswer(answer);
            error.value = null;
        }catch(err){
            error.value = err.message
        }
    }

    async function completeChallenge(name) {
        solutions.value = await ChallengeApi.completeChallenge(name);
        complete.value = true;
    }

    async function loadChallenge() {
        // No need to fetch if challenge is already loaded
        if (loadedFromApi.value) return;
    
        try {
            isLoading.value = true;
            const apiData = await ChallengeApi.getChallenge();
            challenge.value = createChallenge(apiData).value;
            complete.value = false;
        } catch (err) {
            error.value = err.message;
        } finally {
            isLoading.value = false;
            loadedFromApi.value = true;
        }
    }

    return { 
        // state
        challenge,
        isLoading,
        error,
        solutions,
        complete,
        // actions
        newChallenge,
        getChallenge,
        submitChallengeAnswer,
        completeChallenge
    };
});