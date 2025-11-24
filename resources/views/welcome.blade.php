<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="flex flex-col gap-4">
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-full text-center">
                    Login
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg w-full text-center">
                        Register
                    </a>
                @endif
            @endif
        </div>
    </x-authentication-card>
</x-guest-layout>
