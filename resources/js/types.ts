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
    readonly content: string;
}

export interface Statistics {
    readonly secrets_created: number;
    readonly secrets_revealed: number;
    readonly secrets_expired: number;
    readonly secrets_burned: number;
}

export interface StatisticsData {
    readonly statistics: Statistics;
}

export type SelectOptions = ReadonlyArray<SelectOption>;

export type SelectOption = {
    value: string | number;
    label: string;
};

export type ButtonType = 'primary' | 'secondary' | 'success' | 'danger' | 'light';

export type IconButtonType = 'success' | 'danger' | 'info' | 'warning' | 'light';

export type ProgressBarType = 'default' | 'success' | 'danger' | 'info' | 'warning' | 'expiration';

export type NotificationType = 'neutral' | 'success' | 'danger' | 'info' | 'warning';

export type FeaturesMap = Record<string, boolean | string>;

export type GetStatisticsResponse = StatisticsData;

export type ListFeaturesResponse = FeaturesMap;

export type GetSecretResponse = { secret: Secret };

export type RevealSecretResponse = SecretContent;

export type StoreSecretResponse = { secret: SecretWithAccessToken };
