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
            'id' => Str::random(64),
            'access_token' => Str::random(64),
            'content' => fake()->sentence(),
            'passphrase' => null,
            'expires_at' => now()->addDay(),
            'revealed_at' => null,
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
     * Indicate that the secret is revealed.
     */
    public function revealed(): static
    {
        return $this->state(fn (array $attributes) => [
            'content' => null,
            'revealed_at' => now(),
        ]);
    }

    /**
     * Indicate that the secret is wiped.
     */
    public function wiped(): static
    {
        return $this->state(fn (array $attributes) => [
            'content' => null,
        ]);
    }

    /**
     * Indicate that the secret should expire in the future.
     */
    public function expiresIn(int $seconds): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->addSeconds($seconds),
        ]);
    }

    /**
     * Indicate that the secret should expire after the current second.
     */
    public function expiresNow(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now(),
        ]);
    }

    /**
     * Indicate that the secret is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->minus(seconds: 1),
        ]);
    }
}
