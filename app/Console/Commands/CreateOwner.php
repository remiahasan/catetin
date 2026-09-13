<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

use function Laravel\Prompts\text;
use function Laravel\Prompts\password as promptPassword;

class CreateOwner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-owner
        {email : Login email of the owner account}
        {--name= : Full name (prompted when omitted)}
        {--password= : Password, min 8 chars (prompted securely when omitted)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create (or promote) a verified owner account. Safe to re-run.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email')));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error("Invalid email address: {$email}");
            return self::FAILURE;
        }

        $name = $this->option('name') ?: text('Full name');

        $password = $this->option('password');
        if (! $password) {
            $password = promptPassword('Password (min 8 characters)');
        }
        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters.');
            return self::FAILURE;
        }

        // NOTE: pass the password plain — the User model's `hashed` cast
        // hashes it automatically. Pre-hashing here would double-hash it.
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'role' => 'owner',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->info("Owner account ready: {$user->email} (id {$user->id}).");
        $this->line('Log in at /login to reach the owner dashboard.');

        return self::SUCCESS;
    }
}
