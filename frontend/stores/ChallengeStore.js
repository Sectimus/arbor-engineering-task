import { ref } from 'vue';
import { defineStore } from 'pinia';
import ChallengeApi from '../services/ChallengeApi';

export const useChallengeStore = defineStore('challenge', () => {
    const challenge = ref([]);
    const isLoading = ref(false);
    const error = ref(null);
    const loadedFromApi = ref(false);

    async function getChallenge() {
        await loadChallenge(); // If our challenge is not loaded yet
        //TODO challenge being null?

        return await challenge.value;
    }

    async function submitChallengeAnswer(answer) {
        challenge.value = await ChallengeApi.submitChallengeAnswer(answer);
        let val = await challenge.value
        let asd = 2;
    }

    async function completeChallenge(challenge) {
        alert("complete");
    }

    async function loadChallenge(fresh = false) {
        // No need to fetch if challenge are already loaded
        if (!fresh && loadedFromApi.value) return;
    
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
        // actions
        getChallenge,
        submitChallengeAnswer,
        completeChallenge
    };
});