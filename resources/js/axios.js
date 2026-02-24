import axios from 'axios';
import { useNotificationStore } from '@/stores/notifications';

function extractErrorMessage(error) {
    const data = error.response?.data;

    return data?.message || error.message || 'An unexpected error occurred.';
}

axios.interceptors.response.use(
    (response) => response,
    (error) => {
        const notify = useNotificationStore();

        const message = extractErrorMessage(error);

        notify.error(message);

        return Promise.reject(error);
    },
);

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

export default axios;
