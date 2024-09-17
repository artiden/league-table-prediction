import axios from 'axios';

const api = axios.create({
    baseURL: import.meta.env.APP_URL
});

export default api;
