import axios from 'axios';

export default(url='/api') => {
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
            // You can add custom logic here before the request is sent
            return config;
        },
        (error) => {
            return Promise.reject(error);
        }
    );

    // Add a response interceptor
    api.interceptors.response.use(
        (response) => {
            return response.data; // Return only the data part of the response
        },
        (error) => {
            return Promise.reject(error);
        }
    );

    return api;
}