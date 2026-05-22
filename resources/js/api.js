import { http } from '@/http';

export const getStatistics = () => http.get('/api/statistics');

export const getFeatures = () => http.get('/api/features');

export const deactivateFeature = (feature) => http.post(`/api/features/${feature}/deactivate`);

export const storeSecret = (data) => http.post('/api/secrets', data).then((response) => response.secret);

export const getSecret = (id, accessToken = undefined) =>
    http
        .get(`/api/secrets/${id}`, {
            headers: {
                ...(accessToken && { 'X-Access-Token': accessToken }),
            },
        })
        .then((response) => response.secret);

export const revealSecret = (id, data) => http.post(`/api/secrets/${id}/reveal`, data).then((response) => response.content);

export const burnSecret = (id, data) => http.delete(`/api/secrets/${id}`, { body: data });
