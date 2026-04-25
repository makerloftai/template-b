<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';

    public function mount(): void
    {
        $this->name = Auth::user()?->name ?? '';
    }

    #[On('profile-updated')]
    public function refreshName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<flux:header container class="bg-white border-b border-gray-100">
    <flux:brand href="{{ route('dashboard') }}" wire:navigate class="max-lg:hidden">
        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
    </flux:brand>

    <flux:navbar class="-mb-px max-lg:hidden">
        <flux:navbar.item href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')" wire:navigate>
            {{ __('Dashboard') }}
        </flux:navbar.item>
    </flux:navbar>

    <flux:spacer />

    <flux:dropdown position="bottom" align="end">
        <flux:profile :name="$name" />

        <flux:menu>
            <flux:menu.item href="{{ route('profile') }}" wire:navigate icon="user-circle">
                {{ __('Profile') }}
            </flux:menu.item>
            <flux:menu.separator />
            <flux:menu.item wire:click="logout" icon="arrow-right-start-on-rectangle">
                {{ __('Log Out') }}
            </flux:menu.item>
        </flux:menu>
    </flux:dropdown>

    <flux:sidebar stashable sticky class="lg:hidden bg-white border-r border-gray-100">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
        <flux:brand href="{{ route('dashboard') }}" wire:navigate>
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </flux:brand>
        <flux:navlist variant="outline">
            <flux:navlist.item href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </flux:navlist.item>
            <flux:navlist.item href="{{ route('profile') }}" :current="request()->routeIs('profile')" wire:navigate>
                {{ __('Profile') }}
            </flux:navlist.item>
            <flux:navlist.item wire:click="logout" as="button">
                {{ __('Log Out') }}
            </flux:navlist.item>
        </flux:navlist>
    </flux:sidebar>

    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
</flux:header>
