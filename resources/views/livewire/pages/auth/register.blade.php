<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <form wire:submit="register" class="flex flex-col gap-4">
        <flux:input
            wire:model="name"
            label="{{ __('Name') }}"
            type="text"
            required
            autofocus
            autocomplete="name"
        />

        <flux:input
            wire:model="email"
            label="{{ __('Email') }}"
            type="email"
            required
            autocomplete="username"
        />

        <flux:input
            wire:model="password"
            label="{{ __('Password') }}"
            type="password"
            required
            autocomplete="new-password"
            viewable
        />

        <flux:input
            wire:model="password_confirmation"
            label="{{ __('Confirm Password') }}"
            type="password"
            required
            autocomplete="new-password"
            viewable
        />

        <div class="flex items-center justify-end gap-4">
            <flux:link href="{{ route('login') }}" wire:navigate variant="ghost">
                {{ __('Already registered?') }}
            </flux:link>

            <flux:button type="submit" variant="primary">
                {{ __('Register') }}
            </flux:button>
        </div>
    </form>
</div>
