import { ref } from 'vue';
import { defineStore } from 'pinia';
import ChallengeApi from '../services/ChallengeApi';

export const useChallengeStore = defineStore('challenge', () => {
    const challenge = ref([]);
    const isLoading = ref(false);
    const error = ref(null);
    const loadedFromApi = ref(false);

    async function getChallenge(id) {
        await loadChallenge(); // If our challengelist is not loaded yet

        let challenge = challenge.value.find(challenge => challenge.id === id) || null;

        if (!challenge) {
            throw new Error(`Challenge with id ${id} not found`);
        }

        return challenge;
    }

    async function submitChallenge(challenge) {
        alert("submit");
    }

    async function completeChallenge(challenge) {
        alert("complete");
    }

    async function loadChallenge(fresh = false) {
        // No need to fetch if challenge are already loaded
        if (!fresh && loadedFromApi.value) return;
    
        try {
            isLoading.value = true;
            challenge.value = await ChallengeApi.getchallenge();
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
        loadChallenge,
        submitChallenge,
        completeChallenge
    };
});