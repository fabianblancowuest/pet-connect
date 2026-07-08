<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'PetConnect') }} — {{ __('Quiénes somos') }}</title>
        <link rel="icon" href="/images/logo.png" type="image/png">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900">
        <header class="sticky top-0 z-50 border-b border-zinc-200/60 bg-white/80 backdrop-blur-xl dark:border-zinc-800/60 dark:bg-zinc-900/80">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <a href="/" class="flex items-center gap-2.5">
                    <div class="flex size-10 items-center justify-center rounded-lg">
                        <x-app-logo-icon class="size-9" />
                    </div>
                    <span class="text-lg font-semibold tracking-tight text-zinc-900 dark:text-white">
                        PetConnect
                    </span>
                </a>

                <!-- Desktop nav -->
                <nav class="hidden sm:flex items-center gap-2">
                    <flux:button :href="route('about')" wire:navigate variant="ghost" class="shrink-0">
                        {{ __('Quiénes somos') }}
                    </flux:button>
                    <button
                        type="button"
                        class="flex size-9 shrink-0 items-center justify-center rounded-lg text-zinc-500 transition-colors hover:bg-zinc-100 hover:text-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-200"
                        onclick="
                            document.documentElement.classList.toggle('dark');
                            localStorage.setItem('flux.appearance', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
                        "
                    >
                        <svg class="size-5 block dark:hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg class="size-5 hidden dark:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>

                    <flux:button :href="route('pets.catalog')" wire:navigate variant="ghost" class="shrink-0">
                        {{ __('Mascotas') }}
                    </flux:button>

                    @auth
                        <flux:button :href="route('dashboard')" wire:navigate variant="primary" class="shrink-0">
                            {{ __('Ir al panel principal') }}
                        </flux:button>
                    @else
                        <flux:button :href="route('login')" wire:navigate variant="ghost" class="shrink-0">
                            {{ __('Ingresar') }}
                        </flux:button>
                        @if (Route::has('register'))
                            <flux:button :href="route('register')" wire:navigate variant="primary" class="shrink-0">
                                {{ __('Registrarse') }}
                            </flux:button>
                        @endif
                    @endauth
                </nav>

                <!-- Mobile hamburger -->
                <div class="flex sm:hidden items-center gap-1" id="mobile-menu-container">
                    <button
                        type="button"
                        class="flex size-9 items-center justify-center rounded-lg text-zinc-500 transition-colors hover:bg-zinc-100 hover:text-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-200"
                        onclick="
                            document.documentElement.classList.toggle('dark');
                            localStorage.setItem('flux.appearance', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
                        "
                    >
                        <svg class="size-5 block dark:hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg class="size-5 hidden dark:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>
                    <button onclick="toggleMobileMenu()" type="button" id="mobile-menu-btn" class="flex size-9 items-center justify-center rounded-lg text-zinc-500 transition-colors hover:bg-zinc-100 hover:text-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-200">
                        <svg id="menu-icon-open" class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg id="menu-icon-close" class="size-6 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
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
                        {{ __('Quiénes somos') }}
                    </h1>

                    <p class="mx-auto mt-6 max-w-2xl text-lg leading-relaxed text-zinc-500 dark:text-zinc-400">
                        {{ __('Conocé la historia detrás de PetConnect y el equipo que lo hizo posible.') }}
                    </p>
                </div>
            </section>

            <section class="border-t border-zinc-100 dark:border-zinc-800">
                <div class="mx-auto max-w-6xl px-6 py-20 lg:py-28">
                    <div class="mx-auto mb-16 max-w-3xl">
                        <h2 class="mb-6 text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                            {{ __('Nuestra historia') }}
                        </h2>
                        <p class="mb-4 text-lg leading-relaxed text-zinc-600 dark:text-zinc-300">
                            {{ __('PetConnect nació como un proyecto integrador de la Tecnicatura Universitaria en Programación (TUP) en la Universidad Tecnológica Nacional. Lo que comenzó como un trabajo académico se transformó en una plataforma real pensada para facilitar la conexión entre refugios, rescatistas y personas que buscan adoptar una mascota.') }}
                        </p>
                        <p class="text-lg leading-relaxed text-zinc-600 dark:text-zinc-300">
                            {{ __('Nuestro objetivo es simplificar el proceso de adopción, brindar visibilidad a las organizaciones que trabajan por el bienestar animal y ayudar a que cada mascota encuentre un hogar responsable donde sea feliz.') }}
                        </p>
                    </div>

                    <div class="mx-auto mb-16 max-w-3xl">
                        <h2 class="mb-6 text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                            {{ __('La organización') }}
                        </h2>
                        <p class="text-lg leading-relaxed text-zinc-600 dark:text-zinc-300">
                            {{ __('PetConnect es una plataforma digital abierta que permite a refugios y rescatistas publicar mascotas en adopción, gestionar solicitudes y encontrar adoptantes responsables. Creemos en el poder de la tecnología para generar un impacto positivo en la sociedad y en la vida de los animales.') }}
                        </p>
                    </div>

                    @php $developers = \App\Models\Developer::orderBy('sort_order')->get(); @endphp

                    <div class="mx-auto max-w-5xl">
                        <h2 class="mb-12 text-center text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                            {{ __('El equipo de desarrollo') }}
                        </h2>

                        <div class="grid gap-8 md:grid-cols-3">
                            @forelse ($developers as $dev)
                                <div class="group rounded-2xl border border-zinc-200 bg-white p-8 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/5 dark:border-zinc-700 dark:bg-zinc-800">
                                    <div class="mx-auto mb-5 flex size-20 items-center justify-center overflow-hidden rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        @if ($dev->image)
                                            <img src="{{ $dev->image }}" alt="{{ $dev->name }}" class="size-full object-cover" />
                                        @else
                                            <span class="text-2xl font-bold">{{ \Illuminate\Support\Str::initials($dev->name) }}</span>
                                        @endif
                                    </div>
                                    <h3 class="mb-1 text-xl font-semibold text-zinc-900 dark:text-white">{{ $dev->name }}</h3>
                                    @if ($dev->role)
                                        <p class="mb-3 text-sm font-medium text-emerald-600 dark:text-emerald-400">{{ $dev->role }}</p>
                                    @endif
                                    @if ($dev->description)
                                        <p class="text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">{{ $dev->description }}</p>
                                    @endif
                                </div>
                            @empty
                                <div class="col-span-3 text-center text-zinc-500 dark:text-zinc-400">
                                    {{ __('Información del equipo próximamente.') }}
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Mobile sidebar overlay -->
        <div id="mobile-sidebar" class="fixed inset-0 z-50 hidden sm:hidden">
            <div onclick="toggleMobileMenu()" class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>
            <div class="fixed top-0 right-0 bottom-0 w-72 max-w-[85vw] bg-white dark:bg-zinc-900 shadow-2xl">
                <div class="flex items-center justify-between border-b border-zinc-200 px-5 py-4 dark:border-zinc-800">
                    <span class="font-semibold text-zinc-900 dark:text-white">{{ __('Menú') }}</span>
                    <button onclick="toggleMobileMenu()" type="button" class="flex size-8 items-center justify-center rounded-lg text-zinc-400 hover:bg-zinc-100 hover:text-zinc-600 dark:hover:bg-zinc-800 dark:hover:text-zinc-300">
                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <nav class="flex flex-col gap-1 p-5">
                    <a href="{{ route('about') }}" wire:navigate onclick="toggleMobileMenu()"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800">
                        <flux:icon name="information-circle" class="size-5" />
                        {{ __('Quiénes somos') }}
                    </a>
                    <a href="{{ route('pets.catalog') }}" wire:navigate onclick="toggleMobileMenu()"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800">
                        <flux:icon name="heart" class="size-5" />
                        {{ __('Mascotas') }}
                    </a>
                    @auth
                        <hr class="my-2 border-zinc-200 dark:border-zinc-800" />
                        <a href="{{ route('dashboard') }}" wire:navigate onclick="toggleMobileMenu()"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800">
                            <flux:icon name="home" class="size-5" />
                            {{ __('Ir al panel principal') }}
                        </a>
                    @else
                        <hr class="my-2 border-zinc-200 dark:border-zinc-800" />
                        <a href="{{ route('login') }}" wire:navigate onclick="toggleMobileMenu()"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-950/30">
                            <flux:icon name="arrow-right-start-on-rectangle" class="size-5" />
                            {{ __('Ingresar') }}
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" wire:navigate onclick="toggleMobileMenu()"
                                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600">
                                <flux:icon name="user-plus" class="size-5" />
                                {{ __('Registrarse') }}
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
        </div>

        <script>
            function toggleMobileMenu() {
                const sidebar = document.getElementById('mobile-sidebar');
                const iconOpen = document.getElementById('menu-icon-open');
                const iconClose = document.getElementById('menu-icon-close');
                sidebar.classList.toggle('hidden');
                iconOpen.classList.toggle('hidden');
                iconClose.classList.toggle('hidden');
            }
        </script>

        <footer class="border-t border-zinc-200 dark:border-zinc-800">
            <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-6 py-8 sm:flex-row">
                <div class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400">
                    <div class="flex size-8 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-7" />
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
