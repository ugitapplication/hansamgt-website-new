<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create';

    protected $description = 'Create a local development administrator account';

    /**
     * Create an administrator explicitly.
     *
     * This command is a development convenience only. It refuses to run outside
     * the local environment, never accepts a hardcoded password, and never
     * promotes an existing account silently.
     */
    public function handle(): int
    {
        if (! app()->environment('local')) {
            $this->error('The admin:create command can only be run in the local environment.');

            return self::FAILURE;
        }

        $name = trim((string) $this->ask('Name'));
        $email = trim((string) $this->ask('Email address'));
        $password = (string) $this->secret('Password (min. 12 characters)');
        $confirmation = (string) $this->secret('Confirm password');

        $validator = Validator::make(
            [
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $confirmation,
            ],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:12', 'confirmed'],
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->is_admin = true;
        $user->email_verified_at = now();
        $user->save();

        $this->info("Administrator {$user->email} created.");

        return self::SUCCESS;
    }
}
