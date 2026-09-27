export type Secret = {
    readonly id: string;
    readonly created_at: string;
    readonly expires_at: string;
    readonly revealed_at: string | null;
    readonly is_passphrase_protected: boolean;
    readonly is_expired: boolean;
    readonly is_revealed: boolean;
    readonly is_available: boolean;
    readonly is_burned?: boolean;
};

export type SecretWithAccessToken = Secret & {
    readonly access_token: string;
};

export type SecretContent = {
    readonly content: string;
};

export type Statistics = {
    readonly secrets_created: number;
    readonly secrets_revealed: number;
    readonly secrets_expired: number;
    readonly secrets_burned: number;
};

export type StatisticsData = {
    readonly statistics: Statistics;
};

export type SelectOptions = ReadonlyArray<SelectOption>;

export type SelectOption = {
    readonly value: string | number;
    readonly label: string;
};

export type ButtonType = 'primary' | 'secondary' | 'success' | 'danger' | 'light';

export type IconButtonType = 'success' | 'danger' | 'info' | 'warning' | 'light';

export type ProgressBarType = 'default' | 'success' | 'danger' | 'info' | 'warning' | 'expiration';

export type NotificationType = 'neutral' | 'success' | 'danger' | 'info' | 'warning';

export type FeaturesMap = Record<string, boolean | string>;

export type GetStatisticsResponse = StatisticsData;

export type ListFeaturesResponse = FeaturesMap;

export type GetSecretResponse = JsonApiResource<Secret>;

export type GetSecretReceiptResponse = JsonApiResource<Secret>;

export type StoreSecretResponse = JsonApiResource<SecretWithAccessToken>;

export type RevealSecretResponse = SecretContent;

export type JsonApiResource<TAttributes> = {
    data: {
        id: string;
        type: string;
        attributes: TAttributes;
    };
};
