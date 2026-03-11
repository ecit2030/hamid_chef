import axios from 'axios';
window.axios = axios;

window.axios.defaults.baseURL = window.location.origin;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

// CSRF: Axios يقرأ XSRF-TOKEN من الكوكي تلقائياً ويرسلها في X-XSRF-TOKEN
// Laravel يضيف الكوكي في كل response - لا تستخدم meta tag (يمنع التحديث ويسبب 419)
