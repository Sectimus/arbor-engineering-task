import Api from './Api';

export default {
    getChallenge: async () => {
        try {
            const response = await Api().get('/challenge');
            return response.data;
        } catch (error) {
            if (error.response?.data) {
                throw {
                    message: error.response.data.error || 'Validation failed',
                };
            }
            throw {
                message: 'Failed to get challenge',
            };
        }
    },

    newChallenge: async () => {
        try {
            const response = await Api().get('/challenge/new');
            return response.data;
        } catch (error) {
            if (error.response?.data) {
                throw {
                    message: error.response.data.error || 'Validation failed',
                };
            }
            throw {
                message: 'Failed to submit challenge',
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
                throw {
                    message: error.response.data.error || 'Validation failed',
                };
            }
            throw {
                message: 'Failed to submit challenge',
            };
        }
    },

    completeChallenge: async (name) => {
        try {
            //TODO pull the form data directly
            var bodyFormData = new FormData();
            bodyFormData.append('name', name);

            const response = await Api().post('/challenge/complete', bodyFormData);
            return response.data.solutions;
        } catch (error) {
            if (error.response?.data) {
                throw {
                    message: error.response.data.error || 'Validation failed',
                };
            }
            throw {
                message: 'Failed to complete challenge',
            };
        }
    },
}