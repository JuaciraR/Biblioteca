<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6">
        <div class="space-y-6">
            <!-- Update Profile Information -->
            <div class="bg-white p-6 rounded-lg shadow">
                @livewire('profile.update-profile-information-form')
            </div>

            <!-- Update Password -->
            <div class="bg-white p-6 rounded-lg shadow">
                @livewire('profile.update-password-form')
            </div>

            <!-- Two-Factor Authentication -->
            <div class="bg-white p-6 rounded-lg shadow">
                @livewire('profile.two-factor-authentication-form')
            </div>

            <!-- Logout Other Browser Sessions -->
            <div class="bg-white p-6 rounded-lg shadow">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            <!-- Delete User Account -->
            <div class="bg-white p-6 rounded-lg shadow">
                @livewire('profile.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
