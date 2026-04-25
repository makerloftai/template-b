<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <flux:text class="mb-4">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </flux:text>

    <form wire:submit="confirmPassword" class="flex flex-col gap-4">
        <flux:input
            wire:model="password"
            label="{{ __('Password') }}"
            type="password"
            required
            autocomplete="current-password"
            viewable
        />

        <div class="flex justify-end">
            <flux:button type="submit" variant="primary">
                {{ __('Confirm') }}
            </flux:button>
        </div>
    </form>
</div>
