import Api from './Api';

export default {
    getChallenge: (id) => {
        return Api().get(`/challenge/${id}`);
    },

    getchallenge: (params) => {
        return Api().get('/challenge', { params });
    },

    createChallenge: async (data) => {
        try {
            const response = await Api().post('/challenge', data);
            return response.data;
        } catch (error) {
            if (error.response?.data) {
                // Handle validation errors from Symfony
                throw {
                    message: error.response.data.message || 'Validation failed',
                    errors: error.response.data.errors || {}
                };
            }
            throw {
                message: 'Failed to create challenge',
                errors: {}
            };
        }
    },

    updateChallenge: async (id, data) => {
        try {
            await Api().put(`/challenge/${id}`, data);
        } catch (error) {
            if (error.response?.data) {
                // Handle validation errors from Symfony
                throw {
                    message: error.response.data.message || 'Validation failed',
                    errors: error.response.data.errors || {}
                };
            }
            throw {
                message: 'Failed to update challenge',
                errors: {}
            };
        }
    },

    deleteChallenge: (id) => {
        return Api().delete(`/challenge/${id}`);
    },
}