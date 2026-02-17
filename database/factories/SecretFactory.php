<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Secret>
 */
class SecretFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => Str::random(64),
            'secret_key' => Str::random(64),
            'content' => fake()->sentence(),
        ];
    }

    /**
     * Indicate that the secret is protected by a passphrase.
     */
    public function passphraseProtected(string $passphrase = 'secret'): static
    {
        return $this->state(fn (array $attributes) => [
            'passphrase' => Hash::make($passphrase),
        ]);
    }

    /**
     * Indicate that the secret has expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subHour(),
        ]);
    }

    /**
     * Indicate that the secret has been revealed.
     */
    public function revealed(): static
    {
        return $this->state(fn (array $attributes) => [
            'content' => null,
            'revealed_at' => now(),
        ]);
    }

    /**
     * Indicate that the secret will expire in the future.
     */
    public function expiresIn(int $seconds): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->addSeconds($seconds),
        ]);
    }
}
