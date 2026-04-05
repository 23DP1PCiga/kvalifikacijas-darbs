import axios from 'axios';

window.axios = axios;

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://127.0.0.1:8001';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


