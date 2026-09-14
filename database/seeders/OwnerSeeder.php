<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

/**
 * Production-safe owner bootstrap.
 *
 * Creates (or updates) the initial owner account from environment variables.
 * No credentials are hardcoded: everything comes from SEED_OWNER_* and the
 * seeder refuses weak or missing values instead of guessing.
 *
 * Required env:
 *   SEED_OWNER_EMAIL     login email (validated, lowercased)
 *   SEED_OWNER_PASSWORD  min 12 chars — remove from .env after first use
 * Optional env:
 *   SEED_OWNER_NAME      default "Owner"
 *
 * Idempotent: safe to re-run; matching email is updated, never duplicated.
 */
class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = strtolower(trim((string) env('SEED_OWNER_EMAIL', '')));

        if ($email === '') {
            $this->command?->warn('OwnerSeeder skipped: SEED_OWNER_EMAIL is not set.');

            return;
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException("OwnerSeeder: invalid SEED_OWNER_EMAIL '{$email}'.");
        }

        $password = (string) env('SEED_OWNER_PASSWORD', '');

        if (strlen($password) < 12) {
            throw new RuntimeException('OwnerSeeder: SEED_OWNER_PASSWORD must be at least 12 characters.');
        }

        // NOTE: password stays plain here — the User model's `hashed` cast
        // hashes it on save. Pre-hashing would double-hash and lock out.
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => (string) env('SEED_OWNER_NAME', 'Owner'),
                'password' => $password,
                'role' => 'owner',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command?->info("OwnerSeeder: owner ready: {$user->email} (id {$user->id}).");
    }
}
