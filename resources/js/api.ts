import type { GetSecretResponse, GetStatisticsResponse, ListFeaturesResponse, RevealSecretResponse, StoreSecretResponse } from '@/types';
import { http } from '@/http';

export const getStatistics = () => http.get<GetStatisticsResponse>('/api/statistics');

export const listFeatures = () => http.get<ListFeaturesResponse>('/api/features');

export const deactivateFeature = (feature: string) => http.post(`/api/features/${feature}/deactivate`);

export const storeSecret = (data: object) => http.post<StoreSecretResponse>('/api/secrets', data).then((response) => response.secret);

export const getSecret = (id: string, accessToken?: string) =>
    http
        .get<GetSecretResponse>(`/api/secrets/${id}`, {
            headers: {
                ...(accessToken && { 'X-Access-Token': accessToken }),
            },
        })
        .then((response) => response.secret);

export const revealSecret = (id: string, data: object) =>
    http.post<RevealSecretResponse>(`/api/secrets/${id}/reveal`, data).then((response) => response.content);

export const burnSecret = (id: string, data: object) => http.delete(`/api/secrets/${id}`, { body: data });
