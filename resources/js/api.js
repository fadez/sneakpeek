import axios from '@/axios';

export const storeSecret = (data) => {
    return axios.post('/api/secrets', data).then((response) => response.data.secret);
};

export const getSecret = (id, accessToken = undefined) => {
    return axios.get(`/api/secrets/${id}`, { params: { access_token: accessToken } }).then((response) => response.data.secret);
};

export const revealSecret = (id, data) => {
    return axios.post(`/api/secrets/${id}/reveal`, data).then((response) => response.data.content);
};

export const deleteSecret = (id, data) => {
    return axios.delete(`/api/secrets/${id}`, { data });
};
