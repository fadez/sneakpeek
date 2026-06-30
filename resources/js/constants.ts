import type { NotificationType, SelectOption } from './types';

export const NOTIFICATION_TYPE_ICONS: Record<NotificationType, string> = {
    neutral: 'fa-solid fa-circle-info',
    success: 'fa-solid fa-circle-check',
    danger: 'fa-solid fa-circle-exclamation',
    info: 'fa-solid fa-circle-info',
    warning: 'fa-solid fa-circle-exclamation',
} as const;

export const SECRET_TTL_OPTIONS: ReadonlyArray<SelectOption<number>> = [
    { value: 60, label: 'Expires in 1 minute' },
    { value: 300, label: 'Expires in 5 minutes' },
    { value: 1800, label: 'Expires in 30 minutes' },
    { value: 3600, label: 'Expires in 1 hour' },
    { value: 43200, label: 'Expires in 12 hours' },
    { value: 86400, label: 'Expires in 1 day' },
    { value: 259200, label: 'Expires in 3 days' },
    { value: 604800, label: 'Expires in 7 days' },
    { value: 2592000, label: 'Expires in 30 days' },
    { value: 7776000, label: 'Expires in 90 days' },
] as const;
