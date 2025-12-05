<div class="p-6 max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">{{ __('Create New Administrator Account') }}</h2>

    {{-- Feedback de Sucesso --}}
    @if (session()->has('admin_creation_success'))
        <div class="alert alert-success shadow-lg mb-4">
            <span>{{ session('admin_creation_success') }}</span>
        </div>
    @endif
    
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <form wire:submit.prevent="createAdmin" class="space-y-4">

            {{-- Name --}}
            <div>
                <label for="name" class="font-semibold">{{ __('Name') }}</label>
                <input type="text" wire:model="name" id="name" class="input input-bordered w-full" required />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="font-semibold">{{ __('Email') }}</label>
                <input type="email" wire:model="email" id="email" class="input input-bordered w-full" required />
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="font-semibold">{{ __('Password') }}</label>
                <input type="password" wire:model="password" id="password" class="input input-bordered w-full" required />
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="font-semibold">{{ __('Confirm Password') }}</label>
                <input type="password" wire:model="password_confirmation" id="password_confirmation" class="input input-bordered w-full" required />
                @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="btn btn-primary w-full text-black">{{ __('Create Admin') }}</button>
            </div>
        </form>
    </div>
</div>