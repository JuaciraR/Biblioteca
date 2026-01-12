<div class="p-6 max-w-2xl mx-auto">
    {{-- Título com contraste máximo --}}
    <h2 class="text-3xl font-black mb-8 text-gray-900 uppercase tracking-tighter italic">
        {{ __('Create New Administrator Account') }}
    </h2>

    {{-- Feedback de Sucesso Reforçado --}}
    @if (session()->has('admin_creation_success'))
        <div class="alert alert-success shadow-lg mb-6 border-2 border-emerald-500 bg-emerald-50 text-emerald-900 font-black uppercase text-xs">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('admin_creation_success') }}</span>
        </div>
    @endif
    
    <div class="bg-white p-8 rounded-[2rem] shadow-2xl border-2 border-gray-100">
        <form wire:submit.prevent="createAdmin" class="space-y-6">

            {{-- Name --}}
            <div>
                <label for="name" class="block mb-2 text-sm font-black text-gray-900 uppercase tracking-widest italic">{{ __('Name') }}</label>
                <input type="text" wire:model="name" id="name" 
                       placeholder="Full Name"
                       class="input input-bordered w-full border-2 border-gray-400 text-gray-900 font-bold focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600 bg-white h-12" required />
                @error('name') <span class="text-rose-600 font-black text-[10px] mt-1 uppercase tracking-widest">{{ $message }}</span> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block mb-2 text-sm font-black text-gray-900 uppercase tracking-widest italic">{{ __('Email Address') }}</label>
                <input type="email" wire:model="email" id="email" 
                       placeholder="admin@example.com"
                       class="input input-bordered w-full border-2 border-gray-400 text-gray-900 font-bold focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600 bg-white h-12" required />
                @error('email') <span class="text-rose-600 font-black text-[10px] mt-1 uppercase tracking-widest">{{ $message }}</span> @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block mb-2 text-sm font-black text-gray-900 uppercase tracking-widest italic">{{ __('Password') }}</label>
                <input type="password" wire:model="password" id="password" 
                       placeholder="••••••••"
                       class="input input-bordered w-full border-2 border-gray-400 text-gray-900 font-bold focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600 bg-white h-12" required />
                @error('password') <span class="text-rose-600 font-black text-[10px] mt-1 uppercase tracking-widest">{{ $message }}</span> @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block mb-2 text-sm font-black text-gray-900 uppercase tracking-widest italic">{{ __('Confirm Password') }}</label>
                <input type="password" wire:model="password_confirmation" id="password_confirmation" 
                       placeholder="••••••••"
                       class="input input-bordered w-full border-2 border-gray-400 text-gray-900 font-bold focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600 bg-white h-12" required />
                @error('password_confirmation') <span class="text-rose-600 font-black text-[10px] mt-1 uppercase tracking-widest">{{ $message }}</span> @enderror
            </div>

            <div class="pt-6">
                {{-- Botão com texto branco para contraste contra o fundo primário --}}
                <button type="submit" class="btn btn-primary w-full h-14 text-white font-black uppercase tracking-tighter text-lg shadow-xl hover:bg-indigo-700 border-none">
                    {{ __('Create Admin Account') }}
                </button>
            </div>
        </form>
    </div>
</div>