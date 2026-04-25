<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <flux:heading size="lg">{{ __('Delete Account') }}</flux:heading>
    <flux:text class="mt-1">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </flux:text>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button variant="danger">{{ __('Delete Account') }}</flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-md">
        <form wire:submit="deleteUser" class="space-y-6">
            <flux:heading size="lg">{{ __('Are you sure you want to delete your account?') }}</flux:heading>
            <flux:text>
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </flux:text>

            <flux:input
                wire:model="password"
                label="{{ __('Password') }}"
                type="password"
                placeholder="{{ __('Password') }}"
                viewable
            />

            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button>{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="danger">
                    {{ __('Delete Account') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</section>
