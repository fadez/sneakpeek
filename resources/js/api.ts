import type { GetSecretResponse, GetStatisticsResponse, ListFeaturesResponse, RevealSecretResponse, StoreSecretResponse } from '@/types';
import { http } from '@/http';
import { deactivate as featuresDeactivateRoute, list as featuresListRoute } from '@/routes/api/features';
import {
    destroy as secretsDestroyRoute,
    reveal as secretsRevealRoute,
    show as secretsShowRoute,
    store as secretsStoreRoute,
} from '@/routes/api/secrets';
import { snapshot as statisticsSnapshotRoute } from '@/routes/api/statistics';

export const getStatistics = () => http.wayfinderRequest<GetStatisticsResponse>(statisticsSnapshotRoute());

export const listFeatures = () => http.wayfinderRequest<ListFeaturesResponse>(featuresListRoute());

export const deactivateFeature = (feature: string) => http.wayfinderRequest<void>(featuresDeactivateRoute({ feature }));

export const storeSecret = (data: object) =>
    http.wayfinderRequest<StoreSecretResponse>(secretsStoreRoute(), { body: data }).then((response) => response.secret);

export const getSecret = (id: string, accessToken?: string) =>
    http
        .wayfinderRequest<GetSecretResponse>(secretsShowRoute({ secret: id }), {
            headers: {
                ...(accessToken && { 'X-Access-Token': accessToken }),
            },
        })
        .then((response) => response.secret);

export const revealSecret = (id: string, data: object) =>
    http.wayfinderRequest<RevealSecretResponse>(secretsRevealRoute({ secret: id }), { body: data }).then((response) => response.content);

export const burnSecret = (id: string, data: object) => http.wayfinderRequest<void>(secretsDestroyRoute({ secret: id }), { body: data });
