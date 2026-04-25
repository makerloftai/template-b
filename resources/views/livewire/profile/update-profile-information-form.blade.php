<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <flux:heading size="lg">{{ __('Profile Information') }}</flux:heading>
    <flux:text class="mt-1">
        {{ __("Update your account's profile information and email address.") }}
    </flux:text>

    <form wire:submit="updateProfileInformation" class="mt-6 flex flex-col gap-4">
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

        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
            <flux:callout>
                {{ __('Your email address is unverified.') }}
                <flux:link wire:click.prevent="sendVerification" as="button" variant="ghost">
                    {{ __('Click here to re-send the verification email.') }}
                </flux:link>

                @if (session('status') === 'verification-link-sent')
                    <flux:text class="mt-2 font-medium" color="green">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </flux:text>
                @endif
            </flux:callout>
        @endif

        <div class="flex items-center gap-4">
            <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>

            <flux:text x-data="{ shown: false }" x-init="@this.on('profile-updated', () => { shown = true; setTimeout(() => shown = false, 2000) })" x-show="shown" x-cloak>
                {{ __('Saved.') }}
            </flux:text>
        </div>
    </form>
</section>
