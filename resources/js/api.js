import axios from '@/axios';

export const getStatistics = () => {
    return axios.get('/api/statistics').then((response) => response.data);
};

export const getFeatures = () => {
    return axios.get('/api/features').then((response) => response.data);
};

export const deactivateFeature = (feature) => {
    return axios.post(`/api/features/${feature}/deactivate`);
};

export const storeSecret = (data) => {
    return axios.post('/api/secrets', data).then((response) => response.data.secret);
};

export const getSecret = (id, accessToken = undefined) => {
    return axios
        .get(`/api/secrets/${id}`, {
            // Access token is passed via header instead of query string to prevent
            // it from appearing in server logs, browser history, and referrer headers
            headers: {
                ...(accessToken && { 'X-Access-Token': accessToken }),
            },
        })
        .then((response) => response.data.secret);
};

export const revealSecret = (id, data) => {
    return axios.post(`/api/secrets/${id}/reveal`, data).then((response) => response.data.content);
};

export const burnSecret = (id, data) => {
    return axios.delete(`/api/secrets/${id}`, { data });
};
