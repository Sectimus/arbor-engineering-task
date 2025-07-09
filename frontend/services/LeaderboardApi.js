import Api from './Api';

export default {
    getChampions: async () => {
        try {
            const response = await Api().get('/leaderboard');
            return response.data;
        } catch (error) {
            throw {
                message: 'Failed to load champions'
            };
        }
    },
}