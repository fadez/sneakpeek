<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class ShowSecretRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Accept the access token via header rather than query string to avoid
        // exposing it in server access logs, proxy logs, and browser history
        if ($this->hasHeader('X-Access-Token')) {
            $this->merge([
                'access_token' => $this->header('X-Access-Token'),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'access_token' => ['nullable', 'string'],
        ];
    }
}
