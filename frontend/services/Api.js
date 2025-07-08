import axios from 'axios';

export default(url='http://localhost:8080/api') => {
    const api = axios.create({
        baseURL: url,
        timeout: 10000, // 10 seconds timeout
        headers: {
            'Content-Type': 'application/json',
        },
    });

    // Add a request interceptor
    api.interceptors.request.use(
        (config) => {
            return config;
        },
        (error) => {
            return Promise.reject(error);
        }
    );

    // Add a response interceptor
    api.interceptors.response.use(
        (response) => {
            return response.data; // Be aware we return only the data part of the response
        },
        (error) => {
            return Promise.reject(error);
        }
    );

    return api;
}