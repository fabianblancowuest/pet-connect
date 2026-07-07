<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Panel principal') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="heart" :href="route('pets.catalog')" :current="request()->routeIs('pets.catalog*')" wire:navigate>
                        {{ __('Mascotas') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            @auth
                @php $user = auth()->user(); @endphp

                @if (in_array($user->role, ['rescuer', 'admin']))
                    <flux:sidebar.nav>
                        <flux:sidebar.group class="grid">
                            <flux:sidebar.item icon="building" :href="route('rescuer.organization')" :current="request()->routeIs('rescuer.organization')" wire:navigate>
                                {{ __('Mi refugio') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="paw-print" :href="route('rescuer.pets.index')" :current="request()->routeIs('rescuer.pets.*')" wire:navigate>
                                {{ __('Mis mascotas') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="mail" :href="route('rescuer.requests')" :current="request()->routeIs('rescuer.requests')" wire:navigate>
                                {{ __('Solicitudes') }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    </flux:sidebar.nav>
                @endif

                @if ($user->role === 'admin')
                    <flux:sidebar.nav>
                        <flux:sidebar.group :heading="__('Administración')" class="grid">
                            <flux:sidebar.item icon="users" :href="route('admin.developers.index')" :current="request()->routeIs('admin.developers.*')" wire:navigate>
                                {{ __('Desarrolladores') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="building-office-2" :href="route('admin.organizations.index')" :current="request()->routeIs('admin.organizations.*')" wire:navigate>
                                {{ __('Refugios') }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    </flux:sidebar.nav>
                @endif

                @if ($user->role === 'adopter')
                    <flux:sidebar.nav>
                        <flux:sidebar.group class="grid">
                            <flux:sidebar.item icon="list" :href="route('adoption.my-requests')" :current="request()->routeIs('adoption.my-requests')" wire:navigate>
                                {{ __('Mis solicitudes') }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    </flux:sidebar.nav>
                @endif
            @endauth

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.group class="grid">
                    <flux:sidebar.item icon="information-circle" :href="route('about')" :current="request()->routeIs('about')" wire:navigate>
                        {{ __('Quiénes somos') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="cog" :href="route('profile.edit')" :current="request()->routeIs('profile.edit')" wire:navigate>
                        {{ __('Configuración') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item class="cursor-pointer" x-data="{ dark: document.documentElement.classList.contains('dark') }" x-on:click="
                        dark = !dark;
                        localStorage.setItem('flux.appearance', dark ? 'dark' : 'light');
                        document.documentElement.classList.toggle('dark');
                    ">
                        <div class="flex items-center gap-3">
                            <template x-if="dark">
                                <flux:icon name="sun" class="size-5 shrink-0" />
                            </template>
                            <template x-if="!dark">
                                <flux:icon name="moon" class="size-5 shrink-0" />
                            </template>
                            <span x-text="dark ? 'Modo claro' : 'Modo oscuro'"></span>
                        </div>
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            @auth
                <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
            @else
                <div class="hidden px-4 pb-4 lg:block">
                    <flux:button href="{{ route('login') }}" variant="primary" class="w-full" wire:navigate>
                        {{ __('Iniciar sesión') }}
                    </flux:button>
                </div>
            @endauth
        </flux:sidebar>

        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            @auth
                <flux:dropdown position="top" align="end">
                    <flux:profile
                        :initials="auth()->user()->initials()"
                        icon-trailing="chevron-down"
                    />
                    <flux:menu>
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <flux:avatar
                                        :name="auth()->user()->name"
                                        :initials="auth()->user()->initials()"
                                    />
                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                        <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>
                        <flux:menu.separator />
                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                                {{ __('Configuración') }}
                            </flux:menu.item>
                        </flux:menu.radio.group>
                        <flux:menu.separator />
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item
                                as="button" type="submit"
                                icon="arrow-right-start-on-rectangle"
                                class="w-full cursor-pointer"
                                data-test="logout-button"
                            >
                                {{ __('Cerrar sesión') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            @else
                <flux:button href="{{ route('login') }}" variant="primary" size="sm" wire:navigate>
                    {{ __('Ingresar') }}
                </flux:button>
            @endauth
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
