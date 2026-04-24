import axios from 'axios';
import { useNotificationStore } from '@/stores/notifications';
import { echo } from '@laravel/echo-vue';

/**
 * Extract a human-readable error message from an Axios error object.
 *
 * @param {Object} error
 * @returns {string}
 */
function extractErrorMessage(error) {
    const data = error.response?.data;

    return data?.message || error.message || 'An unexpected error has occurred.';
}

// Set global defaults
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

// Dynamically attach the socket ID on every request
axios.interceptors.request.use((config) => {
    const socketId = echo()?.socketId();

    if (socketId) {
        config.headers['X-Socket-ID'] = socketId;
    }

    return config;
});

// Automatically show error toast notification
axios.interceptors.response.use(
    (response) => response,
    (error) => {
        const notify = useNotificationStore();

        const message = extractErrorMessage(error);

        notify.danger(message);

        return Promise.reject(error);
    },
);

export default axios;
