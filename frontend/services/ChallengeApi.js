import Api from './Api';

export default {
    getChallenge: async () => {
        try {
            const response = await Api().get('/challenge');
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
                message: 'Failed to load challenge',
                errors: {}
            };
        }
    },

    newChallenge: async () => {
        try {
            const response = await Api().get('/challenge/new');
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

    submitChallenge: async (answer) => {
        try {
            await Api().post('/challenge');
        } catch (error) {
            if (error.response?.data) {
                // Handle validation errors from Symfony
                throw {
                    message: error.response.data.message || 'Validation failed',
                    errors: error.response.data.errors || {}
                };
            }
            throw {
                message: 'Failed to submit challenge',
                errors: {}
            };
        }
    },
}