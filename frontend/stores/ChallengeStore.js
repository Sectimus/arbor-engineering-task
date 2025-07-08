import { ref } from 'vue';
import { defineStore } from 'pinia';
import ChallengeApi from '../services/ChallengeApi';

export const useChallengeStore = defineStore('challenge', () => {
    const challenge = ref([]);
    const solutions = ref([]);
    const isLoading = ref(false);
    const error = ref(null);
    const loadedFromApi = ref(false);

    async function newChallenge() {
        try {
            isLoading.value = true;
            challenge.value = await ChallengeApi.newChallenge();
        } catch (err) {
            error.value = err.message;
            console.error("Error fetching challenge:", err);
        } finally {
            isLoading.value = false;
            loadedFromApi.value = true;
        }

        return await challenge.value;
    }

    async function getChallenge() {
        await loadChallenge(); // If our challenge is not loaded yet
        if(!challenge.value){
            throw "Problem retrieving challenge"
        }

        return await challenge.value;
    }

    async function submitChallengeAnswer(answer) {
        challenge.value = await ChallengeApi.submitChallengeAnswer(answer);
    }

    async function completeChallenge(name) {
        solutions.value = await ChallengeApi.completeChallenge(name);
    }

    async function loadChallenge() {
        // No need to fetch if challenge are already loaded
        if (loadedFromApi.value) return;
    
        try {
            isLoading.value = true;
            challenge.value = await ChallengeApi.getChallenge();
            
        } catch (err) {
            error.value = err.message;
            console.error("Error fetching challenge:", err);
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
        // actions
        newChallenge,
        getChallenge,
        submitChallengeAnswer,
        completeChallenge
    };
});