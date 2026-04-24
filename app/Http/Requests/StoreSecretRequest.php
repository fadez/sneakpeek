<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTOs\CreateSecretData;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class StoreSecretRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:10000'],
            'passphrase' => ['nullable', 'string', 'min:1', 'max:255'],
            'ttl' => ['required', 'integer', 'min:60', 'max:7776000'], // In seconds, from 1 minute up to 90 days
        ];
    }

    /**
     * Map the validated request data to a DTO.
     */
    public function validatedToDTO(): CreateSecretData
    {
        return new CreateSecretData(
            content: $this->string('content')->value(),
            passphrase: $this->filled('passphrase') ? $this->string('passphrase')->value() : null,
            ttl: $this->integer('ttl'),
        );
    }
}
