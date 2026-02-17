import axios from 'axios';
import { useToast } from 'vue-toastification';

const toast = useToast();

function extractErrorMessage(error) {
    const data = error.response?.data;

    return data?.message || error.message || 'An unexpected error occurred.';
}

axios.interceptors.response.use(
    (response) => response,
    (error) => {
        const message = extractErrorMessage(error);

        toast.error(message);

        return Promise.reject(error);
    },
);

export default axios;
