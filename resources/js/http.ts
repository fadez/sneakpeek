import type { RouteDefinition } from '@/wayfinder';

type Method = 'get' | 'post' | 'put' | 'delete' | 'patch' | 'head' | 'options';

type WayfinderRoute = RouteDefinition<Method>;

type RequestOpts = Omit<RequestInit, 'headers' | 'body'> & {
    headers?: HeadersInit;
    body?: unknown;
};

type BeaconData = Record<string, string | number | boolean>;

export type RequestError = {
    status: number;
    message: string;
    [key: string]: unknown;
};

type ErrorHandler = (error: RequestError) => void;

type SocketIdResolver = () => string | undefined;

let errorHandler: ErrorHandler | null = null;
let socketIdResolver: SocketIdResolver | null = null;

/**
 * Register a global handler that runs on every failed request.
 * You can use this to show a toast or log the error.
 * The error is still thrown afterwards.
 *
 * Call this once at app boot, e.g. in app.ts.
 *
 * @example
 * onRequestError((error) => {
 *     useNotificationStore().danger(error.message ?? 'An unexpected error has occurred.');
 * });
 */
export function onRequestError(handler: ErrorHandler | null): void {
    errorHandler = handler;
}

/**
 * Register a function that returns the Laravel Echo socket ID.
 * The socket ID will automatically be attached to every outgoing request as an "X-Socket-ID" header.
 *
 * Call this once at app boot, e.g. in app.ts.
 *
 * @example
 * onResolveSocketId(() => echo().socketId());
 */
export function onResolveSocketId(resolver: SocketIdResolver | null): void {
    socketIdResolver = resolver;
}

function throwRequestError(error: RequestError): never {
    errorHandler?.(error);

    throw error;
}

export function getCookie(name: string): string | null {
    const match = document.cookie.match(new RegExp(`(^|;\\s*)${name}=([^;]*)`));

    return match ? decodeURIComponent(match[2]) : null;
}

export function getXsrfToken(): string {
    return getCookie('XSRF-TOKEN') ?? '';
}

export function getCsrfToken(): string {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

export function setCsrfToken(token: string): void {
    document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', token);
}

function buildHeaders(headers: HeadersInit = {}): Headers {
    const result = new Headers(headers);

    result.set('X-Requested-With', 'XMLHttpRequest');

    if (!result.has('Accept')) {
        result.set('Accept', 'application/json');
    }

    const xsrf = getXsrfToken();
    if (xsrf) {
        result.set('X-XSRF-TOKEN', xsrf);
    }

    const socketId = socketIdResolver?.();
    if (socketId) {
        result.set('X-Socket-ID', socketId);
    }

    return result;
}

function hasFile(value: unknown): boolean {
    if (value instanceof Blob) {
        return true; // File extends Blob
    }

    if (Array.isArray(value)) {
        return value.some(hasFile);
    }

    if (value && typeof value === 'object') {
        return Object.values(value).some(hasFile);
    }

    return false;
}

function appendToFormData(form: FormData, key: string, value: unknown): void {
    if (value == null) {
        return;
    }

    if (value instanceof Blob) {
        form.append(key, value);
    } else if (Array.isArray(value)) {
        value.forEach((item, i) => appendToFormData(form, `${key}[${i}]`, item));
    } else if (typeof value === 'object') {
        for (const [k, v] of Object.entries(value)) {
            appendToFormData(form, `${key}[${k}]`, v);
        }
    } else if (typeof value === 'boolean') {
        // Convert boolean to "1" or "0" strings, as Laravel's "boolean" validation rule rejects literal "true" and "false" string values
        form.append(key, value ? '1' : '0');
    } else if (typeof value === 'string' || typeof value === 'number') {
        form.append(key, String(value));
    }
}

function buildFormData(data: object): FormData {
    const form = new FormData();

    for (const [key, value] of Object.entries(data)) {
        appendToFormData(form, key, value);
    }

    return form;
}

function buildRequestBody(body: unknown): BodyInit | undefined {
    if (body == null) {
        return undefined;
    }

    if (body instanceof FormData) {
        return body;
    }

    return hasFile(body) ? buildFormData(body as object) : JSON.stringify(body);
}

async function request<T = unknown>(url: string, { body, headers, method = 'GET', ...opts }: RequestOpts = {}): Promise<T> {
    const payload = buildRequestBody(body);
    const requestHeaders = buildHeaders(headers);

    method = method.toUpperCase();

    if (payload instanceof FormData) {
        // PHP only parses multipart on POST, so we need to spoof PUT/PATCH/DELETE via the "_method" field
        if (['PUT', 'PATCH', 'DELETE'].includes(method)) {
            payload.set('_method', method);
            method = 'POST';
        }

        // Browser sets the multipart boundary itself
    } else if (payload !== undefined) {
        requestHeaders.set('Content-Type', 'application/json');
    }

    let response: Response;

    try {
        response = await fetch(url, {
            credentials: 'include',
            headers: requestHeaders,
            body: payload,
            method,
            ...opts,
        });
    } catch (cause) {
        throwRequestError({ status: 0, message: 'Network error', cause });
    }

    if (!response.ok) {
        const errorBody = await response.json().catch(() => ({}));
        throwRequestError({ message: 'Request failed', ...errorBody, status: response.status });
    }

    const text = await response.text();

    if (!text) {
        return null as T;
    }

    try {
        return JSON.parse(text) as T;
    } catch (cause) {
        throwRequestError({ status: response.status, message: 'Invalid JSON response', cause });
    }
}

function beaconRequest(path: string, data: BeaconData = {}): boolean {
    const form = new FormData();

    form.append('_token', getCsrfToken());

    for (const [key, value] of Object.entries(data)) {
        form.append(key, String(value));
    }

    return navigator.sendBeacon(path, form);
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

    patch<T = unknown>(url: string, body?: unknown, opts?: Omit<RequestOpts, 'method' | 'body'>): Promise<T> {
        return request<T>(url, { method: 'PATCH', body, ...opts });
    },

    delete<T = unknown>(url: string, body?: unknown, opts?: Omit<RequestOpts, 'method' | 'body'>): Promise<T> {
        return request<T>(url, { method: 'DELETE', body, ...opts });
    },

    beacon: (path: string, data?: BeaconData) => beaconRequest(path, data),

    wayfinderRequest<T = unknown>(route: WayfinderRoute, opts?: Omit<RequestOpts, 'method'>): Promise<T> {
        return request<T>(route.url, { method: route.method.toUpperCase(), ...opts });
    },
};
