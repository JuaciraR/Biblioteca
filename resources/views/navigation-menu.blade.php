@php
    /** @var \App\Models\User $user */
    $user = Auth::user();
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                    {{-- CART ICON --}}
                    <div class="inline-flex items-center h-16 pt-1">
                        @livewire('cart.cart-icon')
                    </div>

                    {{-- Links para TODOS os usuários autenticados --}}
                    <x-nav-link href="{{ route('authors.index') }}" :active="request()->routeIs('authors.index')">
                        Authors
                    </x-nav-link>

                    <x-nav-link href="{{ route('chat.index') }}" :active="request()->routeIs('chat.*')">
                        <i class="fa-solid fa-fire mr-1 text-orange-500"></i> {{ __('Campfire Chat') }}
                    </x-nav-link>

                    <x-nav-link href="{{ route('books') }}" :active="request()->routeIs('books')">
                        Books
                    </x-nav-link>
                    
                    <x-nav-link href="{{ route('publishers') }}" :active="request()->routeIs('publishers')">
                        Publishers
                    </x-nav-link>
                    
                    <x-nav-link href="{{ route('requests') }}" :active="request()->routeIs('requests')">
                        Requests
                    </x-nav-link>

                    {{-- Links apenas para ADMIN --}}
                    @if($user && $user->role === 'Admin')
                        <x-nav-link href="{{ route('admins.create') }}" :active="request()->routeIs('admins.create')">
                            Create Admin
                        </x-nav-link>
                             
                        <x-nav-link href="{{ route('admin.reviews') }}" :active="request()->routeIs('admin.reviews')">
                            Manage Reviews
                        </x-nav-link>

                        <x-nav-link href="{{ route('logs.index') }}" :active="request()->routeIs('logs.index')">
                            {{ __('Audit Logs') }}
                        </x-nav-link>

                        <x-nav-link href="{{ route('book.google_import') }}" :active="request()->routeIs('book.google_import')">
                            <i class="fa-solid fa-cloud-arrow-down mr-1 text-blue-500"></i> {{ __('Google Import') }}
                        </x-nav-link>

                        <x-nav-link href="{{ route('admin.orders') }}" :active="request()->routeIs('admin.orders')">
                            Manage Orders
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="ms-3 relative flex items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && $user)
                            <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                <img class="size-8 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                            </button>
                        @elseif($user)
                            <span class="inline-flex rounded-md">
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                    {{ $user->name }}
                                    <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </span>
                        @endif
                    </x-slot>

                    <x-slot name="content">
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Manage Account') }}
                        </div>

                        <x-dropdown-link href="{{ route('profile.show') }}">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                            <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                {{ __('API Tokens') }}
                            </x-dropdown-link>
                        @endif

                        <div class="border-t border-gray-200"></div>

                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('authors.index') }}" :active="request()->routeIs('authors.index')">
                Authors
            </x-responsive-nav-link>
            
            <x-responsive-nav-link href="{{ route('chat.index') }}" :active="request()->routeIs('chat.*')">
                Campfire Chat
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('books') }}" :active="request()->routeIs('books')">
                Books
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            @if($user)
                <div class="flex items-center px-4">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="shrink-0 me-3">
                            <img class="size-10 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                        </div>
                    @endif

                    <div>
                        <div class="font-medium text-base text-gray-800">{{ $user->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ $user->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>

                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures() && $user->currentTeam)
                        <div class="border-t border-gray-200"></div>
                        <div class="block px-4 py-2 text-xs text-gray-400">{{ __('Manage Team') }}</div>
                        <x-responsive-nav-link href="{{ route('teams.show', $user->currentTeam->id) }}">
                            {{ __('Team Settings') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endif
        </div>
    </div>
</nav>