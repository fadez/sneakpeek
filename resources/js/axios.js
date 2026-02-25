import axios from 'axios';
import { useNotificationStore } from '@/stores/notifications';

/**
 * Extract a human-readable error message from an Axios error object.
 *
 * @param {Object} error
 * @returns {string}
 */
function extractErrorMessage(error) {
    const data = error.response?.data;

    return data?.message || error.message || 'An unexpected error occurred.';
}

// Set global defaults
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

axios.interceptors.response.use(
    (response) => response,
    (error) => {
        const notify = useNotificationStore();

        const message = extractErrorMessage(error);

        notify.error(message);

        return Promise.reject(error);
    },
);

export default axios;
