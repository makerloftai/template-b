<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Bootstraps the deployed app's first user from INITIAL_USER_EMAIL
     * + INITIAL_USER_PASSWORD env vars (injected by the MakerLoft app
     * spec). No-ops when either var is missing so the migration is
     * safe to run locally without the vars set.
     *
     * The User model's 'password' cast is 'hashed', so assigning
     * plaintext auto-hashes on save. email_verified_at is set via
     * direct assignment (not in $fillable) so the first sign-in
     * doesn't hit a verification redirect on routes gated by the
     * 'verified' middleware.
     */
    public function up(): void
    {
        $email = env('INITIAL_USER_EMAIL');
        $password = env('INITIAL_USER_PASSWORD');

        if (! $email || ! $password) {
            return;
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->fill([
            'name' => 'Admin',
            'password' => $password,
        ]);
        $user->email_verified_at = now();
        $user->save();
    }

    public function down(): void {}
};
