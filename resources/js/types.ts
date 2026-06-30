export interface Secret {
    id: string;
    created_at: string;
    expires_at: string;
    revealed_at: string | null;
    is_passphrase_protected: boolean;
    is_expired: boolean;
    is_revealed: boolean;
    is_available: boolean;
    is_burned?: boolean;
}

export interface SecretWithAccessToken extends Secret {
    access_token: string;
}

export interface SecretContent {
    content: string;
}

export interface AppStatistics {
    secrets_created: number;
    secrets_revealed: number;
    secrets_expired: number;
    secrets_burned: number;
}

export interface SelectOption<T = string> {
    value: T;
    label: string;
}

export type ButtonType = 'primary' | 'secondary' | 'success' | 'danger' | 'light';

export type IconButtonType = 'success' | 'danger' | 'info' | 'warning' | 'light';

export type FeaturesMap = Record<string, boolean | string>;

export type GetStatisticsResponse = AppStatistics;

export type ListFeaturesResponse = Record<string, boolean | string>;

export type GetSecretResponse = { secret: Secret };

export type RevealSecretResponse = SecretContent;

export type StoreSecretResponse = { secret: SecretWithAccessToken };

export type NotificationType = 'neutral' | 'success' | 'danger' | 'info' | 'warning';
