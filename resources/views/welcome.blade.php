<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'PetConnect') }} — {{ __('Adopción y rescate de mascotas') }}</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900">
        <header class="sticky top-0 z-50 border-b border-zinc-200/60 bg-white/80 backdrop-blur-xl dark:border-zinc-800/60 dark:bg-zinc-900/80">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <a href="/" class="flex items-center gap-2.5">
                    <div class="flex size-9 items-center justify-center rounded-lg bg-emerald-600 text-white">
                        <x-app-logo-icon class="size-5" />
                    </div>
                    <span class="text-lg font-semibold tracking-tight text-zinc-900 dark:text-white">
                        PetConnect
                    </span>
                </a>

                <nav class="flex items-center gap-3">
                    <flux:button :href="route('about')" wire:navigate variant="ghost" class="hidden sm:inline-flex">
                        {{ __('Quiénes somos') }}
                    </flux:button>
                    <button
                        type="button"
                        id="theme-toggle"
                        class="flex size-9 items-center justify-center rounded-lg text-zinc-500 transition-colors hover:bg-zinc-100 hover:text-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-200"
                        onclick="
                            document.documentElement.classList.toggle('dark');
                            localStorage.setItem('flux.appearance', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
                            this.setAttribute('title', document.documentElement.classList.contains('dark') ? '{{ __('Modo claro') }}' : '{{ __('Modo oscuro') }}');
                        "
                        title="{{ __('Modo oscuro') }}"
                    >
                        <svg class="size-5 block dark:hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg class="size-5 hidden dark:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>

                    @auth
                        <flux:button :href="route('dashboard')" wire:navigate variant="primary">
                            {{ __('Ir al panel principal') }}
                        </flux:button>
                    @else
                        <flux:button :href="route('login')" wire:navigate variant="ghost">
                            {{ __('Ingresar') }}
                        </flux:button>
                        @if (Route::has('register'))
                            <flux:button :href="route('register')" wire:navigate variant="primary">
                                {{ __('Registrarse') }}
                            </flux:button>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        <main>
            <section class="relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-emerald-50/50 to-transparent dark:from-emerald-950/10"></div>
                <div class="mx-auto max-w-6xl px-6 pb-24 pt-20 text-center lg:pb-32 lg:pt-28">
                    <div class="mx-auto mb-6 flex w-fit items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-1.5 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300">
                        <flux:icon name="heart" class="size-4" />
                        <span>{{ __('Conectamos mascotas con hogares') }}</span>
                    </div>

                    <h1 class="mx-auto max-w-4xl text-4xl font-bold tracking-tight text-zinc-900 dark:text-white sm:text-5xl lg:text-6xl">
                        {{ __('Dale una segunda oportunidad') }}
                        <span class="gradient-text">{{ __('a quienes más lo necesitan') }}</span>
                    </h1>

                    <p class="mx-auto mt-6 max-w-2xl text-lg leading-relaxed text-zinc-500 dark:text-zinc-400">
                        {{ __('PetConnect es la plataforma que une refugios, rescatistas y adoptantes. Encontrá a tu próximo compañero y transformale la vida.') }}
                    </p>

                    <div class="mt-10 flex items-center justify-center gap-4">
                        <flux:button :href="route('pets.catalog')" wire:navigate variant="primary" class="px-8 h-12 text-base">
                            {{ __('Ver mascotas disponibles') }}
                        </flux:button>
                        @guest
                            <flux:button :href="route('register')" wire:navigate variant="ghost" class="h-12 text-base">
                                {{ __('Crear cuenta') }}
                            </flux:button>
                        @endguest
                    </div>
                </div>
            </section>

            <section class="border-t border-zinc-100 dark:border-zinc-800">
                <div class="mx-auto max-w-6xl px-6 py-20 lg:py-28">
                    <div class="mx-auto mb-16 max-w-2xl text-center">
                        <h2 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                            {{ __('¿Cómo funciona?') }}
                        </h2>
                        <p class="mt-3 text-zinc-500 dark:text-zinc-400">
                            {{ __('Tres pasos simples para cambiar una vida.') }}
                        </p>
                    </div>

                    <div class="grid gap-8 md:grid-cols-3">
                        <div class="group relative rounded-2xl border border-zinc-200 bg-white p-8 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/5 dark:border-zinc-700 dark:bg-zinc-800">
                            <div class="mb-6 flex size-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                                <span class="text-lg font-bold">1</span>
                            </div>
                            <h3 class="mb-3 text-lg font-semibold text-zinc-900 dark:text-white">{{ __('Explorá') }}</h3>
                            <p class="text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">
                                {{ __('Navegá por nuestro catálogo de mascotas disponibles. Filtralas por especie, tamaño, edad y más para encontrar a tu compañero ideal.') }}
                            </p>
                        </div>

                        <div class="group relative rounded-2xl border border-zinc-200 bg-white p-8 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/5 dark:border-zinc-700 dark:bg-zinc-800">
                            <div class="mb-6 flex size-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                                <span class="text-lg font-bold">2</span>
                            </div>
                            <h3 class="mb-3 text-lg font-semibold text-zinc-900 dark:text-white">{{ __('Solicitá') }}</h3>
                            <p class="text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">
                                {{ __('Envianos un mensaje contándonos sobre vos y tu hogar. El refugio se pondrá en contacto para coordinar los próximos pasos.') }}
                            </p>
                        </div>

                        <div class="group relative rounded-2xl border border-zinc-200 bg-white p-8 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/5 dark:border-zinc-700 dark:bg-zinc-800">
                            <div class="mb-6 flex size-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                                <span class="text-lg font-bold">3</span>
                            </div>
                            <h3 class="mb-3 text-lg font-semibold text-zinc-900 dark:text-white">{{ __('Adoptá') }}</h3>
                            <p class="text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">
                                {{ __('Una vez aprobada tu solicitud, solo queda conocerse y darle la bienvenida a un nuevo miembro de la familia.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-emerald-600 dark:bg-emerald-900">
                <div class="mx-auto max-w-6xl px-6 py-20 text-center lg:py-28">
                    <h2 class="text-3xl font-bold tracking-tight text-white">
                        {{ __('¿Sos un refugio o rescatista?') }}
                    </h2>
                    <p class="mx-auto mt-4 max-w-xl text-lg leading-relaxed text-emerald-100">
                        {{ __('Registrá tu organización, publicá mascotas en adopción y gestioná las solicitudes de forma sencilla.') }}
                    </p>
                    <div class="mt-8">
                        <flux:button :href="route('register')" wire:navigate variant="primary" class="bg-white text-emerald-700 hover:bg-emerald-50 px-8 h-12 text-base">
                            {{ __('Sumate como rescatista') }}
                        </flux:button>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-zinc-200 dark:border-zinc-800">
            <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-6 py-8 sm:flex-row">
                <div class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400">
                    <div class="flex size-6 items-center justify-center rounded-md bg-emerald-600 text-white">
                        <x-app-logo-icon class="size-3" />
                    </div>
                    <span>&copy; {{ date('Y') }} PetConnect. {{ __('Todos los derechos reservados.') }}</span>
                </div>
                <div class="flex items-center gap-6 text-sm text-zinc-500 dark:text-zinc-400">
                    <flux:button :href="route('pets.catalog')" wire:navigate variant="ghost" size="sm">
                        {{ __('Mascotas') }}
                    </flux:button>
                    <flux:button :href="route('about')" wire:navigate variant="ghost" size="sm">
                        {{ __('Quiénes somos') }}
                    </flux:button>
                    @guest
                        <flux:button :href="route('login')" wire:navigate variant="ghost" size="sm">
                            {{ __('Ingresar') }}
                        </flux:button>
                    @endguest
                </div>
            </div>
        </footer>
    </body>
</html>
