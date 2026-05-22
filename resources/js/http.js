import { useNotificationStore } from '@/stores/notifications';
import { echo } from '@laravel/echo-vue';

/**
 * @param {string} name
 * @returns {string|null}
 */
function getCookie(name) {
    const match = document.cookie.match(new RegExp(`(^|;\\s*)${name}=([^;]*)`));

    return match ? decodeURIComponent(match[2]) : null;
}

/**
 * @param {Record<string, unknown>} error
 * @returns {string}
 */
function extractErrorMessage(error) {
    return error?.message || 'An unexpected error has occurred.';
}

/**
 * @param {Record<string, unknown>} opts
 * @returns {Record<string, string>}
 */
function buildHeaders(opts) {
    const headers = {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        ...opts.headers,
    };

    const xsrfToken = getCookie('XSRF-TOKEN');

    if (xsrfToken) {
        headers['X-XSRF-TOKEN'] = xsrfToken;
    }

    const socketId = echo()?.socketId();

    if (socketId) {
        headers['X-Socket-ID'] = socketId;
    }

    return headers;
}

/**
 * @param {string} url
 * @param {RequestInit & { body?: unknown }} opts
 * @returns {Promise<unknown>}
 */
async function request(url, { body, headers, ...opts } = {}) {
    const notify = useNotificationStore();

    const hasBody = body !== undefined && body !== null;

    let res;

    try {
        res = await fetch(url, {
            credentials: 'include',
            headers: {
                ...(hasBody && { 'Content-Type': 'application/json' }),
                ...buildHeaders({ headers }),
            },
            body: hasBody ? JSON.stringify(body) : undefined,
            ...opts,
        });
    } catch (e) {
        const error = { status: 0, message: 'Network error', cause: e };

        notify.danger(extractErrorMessage(error));

        throw error;
    }

    if (!res.ok) {
        let error;

        try {
            error = await res.json();
        } catch {
            error = { message: res.statusText };
        }

        const thrown = { status: res.status, ...error };

        notify.danger(extractErrorMessage(thrown));

        throw thrown;
    }

    const text = await res.text();

    if (!text) {
        return null;
    }

    return JSON.parse(text);
}

export const http = {
    get: (url, opts) => request(url, { method: 'GET', ...opts }),
    post: (url, body, opts) => request(url, { method: 'POST', body, ...opts }),
    put: (url, body, opts) => request(url, { method: 'PUT', body, ...opts }),
    delete: (url, opts) => request(url, { method: 'DELETE', ...opts }),
};
