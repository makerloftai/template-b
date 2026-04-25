<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    @if (session('status'))
        <flux:callout class="mb-4">{{ session('status') }}</flux:callout>
    @endif

    <form wire:submit="login" class="flex flex-col gap-4">
        <flux:input
            wire:model="form.email"
            label="{{ __('Email') }}"
            type="email"
            required
            autofocus
            autocomplete="username"
        />

        <flux:input
            wire:model="form.password"
            label="{{ __('Password') }}"
            type="password"
            required
            autocomplete="current-password"
            viewable
        />

        <flux:checkbox wire:model="form.remember" label="{{ __('Remember me') }}" />

        <div class="flex items-center justify-end gap-4">
            @if (Route::has('password.request'))
                <flux:link href="{{ route('password.request') }}" wire:navigate variant="ghost">
                    {{ __('Forgot your password?') }}
                </flux:link>
            @endif

            <flux:button type="submit" variant="primary">
                {{ __('Log in') }}
            </flux:button>
        </div>
    </form>
</div>
