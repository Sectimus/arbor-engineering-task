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

    submitChallengeAnswer: async (answer) => {
        try {
            //TODO pull the form data directly
            var bodyFormData = new FormData();
            bodyFormData.append('answer', answer);

            const response = await Api().post('/challenge', bodyFormData);
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
                message: 'Failed to submit challenge',
                errors: {}
            };
        }
    },

    completeChallenge: async (name) => {
        try {
            //TODO pull the form data directly
            var bodyFormData = new FormData();
            bodyFormData.append('name', name);

            const response = await Api().post('/challenge/complete', bodyFormData);
            return response.data;
        } catch (error) {
            throw {
                message: 'Failed to complete challenge',
                errors: {}
            };
        }
    },
}