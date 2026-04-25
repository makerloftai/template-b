<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <flux:text class="mb-4">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </flux:text>

    @if (session('status'))
        <flux:callout class="mb-4">{{ session('status') }}</flux:callout>
    @endif

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-4">
        <flux:input
            wire:model="email"
            label="{{ __('Email') }}"
            type="email"
            required
            autofocus
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary">
                {{ __('Email Password Reset Link') }}
            </flux:button>
        </div>
    </form>
</div>
