import { ref } from 'vue';
import { defineStore } from 'pinia';
import LeaderboardApi from '../services/LeaderboardApi';

export const useLeaderboardStore = defineStore('leaderboard', () => {
    const champions = ref([]);
    const isLoading = ref(false);
    const error = ref(null);
    const loadedFromApi = ref(false);

    async function getChampions() {
        await loadLeaderboard(); // ALWAYS update the leaderboard, regardless
        if(!champions.value){
            throw "Problem retrieving leaderboard"
        }

        return await champions.value;
    }

    async function loadLeaderboard() {    
        try {
            isLoading.value = true;
            champions.value = await LeaderboardApi.getChampions();
            
        } catch (err) {
            error.value = err.message;
            console.error("Error fetching leaderboard:", err);
        } finally {
            isLoading.value = false;
            loadedFromApi.value = true;
        }
    }

    return { 
        // state
        champions,
        // actions
        getChampions,
    };
});