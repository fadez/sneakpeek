import { useNotificationStore } from '@/stores/notifications';
import { echo } from '@laravel/echo-vue';

type RequestOpts = Omit<RequestInit, 'headers' | 'body'> & {
    headers?: HeadersInit;
    body?: unknown;
};

interface RequestError {
    status: number;
    message: string;
    [key: string]: unknown;
}

function getCookie(name: string): string | null {
    const match = document.cookie.match(new RegExp(`(^|;\\s*)${name}=([^;]*)`));

    return match ? decodeURIComponent(match[2]) : null;
}

function extractErrorMessage(error: RequestError): string {
    return error.message ?? 'An unexpected error has occurred.';
}

function throwRequestError(error: RequestError): never {
    const notify = useNotificationStore();

    notify.danger(extractErrorMessage(error));

    throw error;
}

function buildHeaders(headers: HeadersInit = {}): Headers {
    const result = new Headers(headers);

    result.set('Accept', 'application/json');
    result.set('X-Requested-With', 'XMLHttpRequest');

    const xsrf = getCookie('XSRF-TOKEN');
    if (xsrf) {
        result.set('X-XSRF-TOKEN', xsrf);
    }

    const socketId = echo()?.socketId();
    if (socketId) {
        result.set('X-Socket-ID', socketId);
    }

    return result;
}

async function request<T = unknown>(url: string, { body, headers, ...opts }: RequestOpts = {}): Promise<T> {
    const hasBody = body != null;

    const requestHeaders = buildHeaders(headers);

    if (hasBody) {
        requestHeaders.set('Content-Type', 'application/json');
    }

    let response: Response;

    try {
        response = await fetch(url, {
            credentials: 'include',
            headers: requestHeaders,
            body: hasBody ? JSON.stringify(body) : undefined,
            ...opts,
        });
    } catch (cause) {
        throwRequestError({ status: 0, message: 'Network error', cause });
    }

    if (!response.ok) {
        throwRequestError({ ...(await response.json()), status: response.status });
    }

    const text = await response.text();

    if (!text) {
        return null as T;
    }

    return JSON.parse(text) as Promise<T>;
}

export const http = {
    get<T = unknown>(url: string, opts?: Omit<RequestOpts, 'method'>): Promise<T> {
        return request<T>(url, { method: 'GET', ...opts });
    },

    post<T = unknown>(url: string, body?: unknown, opts?: Omit<RequestOpts, 'method' | 'body'>): Promise<T> {
        return request<T>(url, { method: 'POST', body, ...opts });
    },

    put<T = unknown>(url: string, body?: unknown, opts?: Omit<RequestOpts, 'method' | 'body'>): Promise<T> {
        return request<T>(url, { method: 'PUT', body, ...opts });
    },

    delete<T = unknown>(url: string, opts?: Omit<RequestOpts, 'method'>): Promise<T> {
        return request<T>(url, { method: 'DELETE', ...opts });
    },
};
