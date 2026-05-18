{{-- Публичный layout магазина «Сантехника».
     Семантика: <header> / <nav> / <main> / <footer>, ровно одна <h1> на странице (Req 23.3).
     Адаптивность 320–1920 px через Tailwind responsive-классы (Req 25.1).
     На ширине < 768 px главное меню сворачивается в «гамбургер» через Alpine.js (Req 25.3, 25.4).
     Минимальный размер touch-цели 44×44 px (Req 25.6).
--}}
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Сантехника'))</title>

    @hasSection('description')
        <meta name="description" content="@yield('description')">
    @endif

    {{-- Дополнительные meta-теги, OpenGraph и т.п. — через стек --}}
    @stack('meta')

    {{-- Шрифты --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    {{-- Сборка ассетов через Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Слот для JSON-LD микроразметки (Req 23.6) --}}
    @stack('jsonld')
</head>
<body class="font-sans antialiased min-h-screen flex flex-col bg-gray-50 text-gray-900">

    {{-- ШАПКА: логотип + горизонтальное меню (md+) или «гамбургер» (< md) --}}
    <header class="bg-white shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="mx-auto max-w-screen-2xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">

                {{-- Логотип --}}
                <a href="{{ url('/') }}"
                   class="inline-flex items-center min-h-touch min-w-touch px-2 text-xl md:text-2xl font-bold text-blue-700 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-md">
                    Сантехника
                </a>

                {{-- Главное горизонтальное меню (md+) --}}
                <nav class="hidden md:block" aria-label="Главное меню">
                    <ul class="flex items-center gap-1 lg:gap-2">
                        <li><a href="{{ url('/') }}" class="inline-flex items-center min-h-touch px-3 py-2 rounded-md text-sm lg:text-base text-gray-700 hover:text-blue-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">Главная</a></li>
                        <li><a href="{{ url('/catalog') }}" class="inline-flex items-center min-h-touch px-3 py-2 rounded-md text-sm lg:text-base text-gray-700 hover:text-blue-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">Каталог</a></li>
                        <li><a href="{{ url('/about') }}" class="inline-flex items-center min-h-touch px-3 py-2 rounded-md text-sm lg:text-base text-gray-700 hover:text-blue-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">О нас</a></li>
                        <li><a href="{{ url('/delivery') }}" class="inline-flex items-center min-h-touch px-3 py-2 rounded-md text-sm lg:text-base text-gray-700 hover:text-blue-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">Доставка</a></li>
                        <li><a href="{{ url('/warranty') }}" class="inline-flex items-center min-h-touch px-3 py-2 rounded-md text-sm lg:text-base text-gray-700 hover:text-blue-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">Гарантия</a></li>
                        <li><a href="{{ url('/contacts') }}" class="inline-flex items-center min-h-touch px-3 py-2 rounded-md text-sm lg:text-base text-gray-700 hover:text-blue-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">Контакты</a></li>
                        <li><a href="{{ url('/blog') }}" class="inline-flex items-center min-h-touch px-3 py-2 rounded-md text-sm lg:text-base text-gray-700 hover:text-blue-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">Блог</a></li>
                        <li><a href="{{ url('/reviews') }}" class="inline-flex items-center min-h-touch px-3 py-2 rounded-md text-sm lg:text-base text-gray-700 hover:text-blue-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">Отзывы</a></li>
                    </ul>
                </nav>

                {{-- Кнопка-«гамбургер» (< md) --}}
                <button type="button"
                        class="md:hidden inline-flex items-center justify-center min-h-touch min-w-touch p-2 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :aria-expanded="mobileMenuOpen.toString()"
                        aria-controls="mobile-main-menu"
                        @click="mobileMenuOpen = ! mobileMenuOpen">
                    <span class="sr-only">Открыть меню</span>
                    <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Мобильное меню (< md): раскрывается по клику на «гамбургер» --}}
            <nav id="mobile-main-menu"
                 class="md:hidden border-t border-gray-200"
                 x-show="mobileMenuOpen"
                 x-cloak
                 x-transition
                 aria-label="Мобильное меню">
                <ul class="py-2">
                    <li><a href="{{ url('/') }}" class="flex items-center min-h-touch px-4 py-3 text-base text-gray-700 hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Главная</a></li>
                    <li><a href="{{ url('/catalog') }}" class="flex items-center min-h-touch px-4 py-3 text-base text-gray-700 hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Каталог</a></li>
                    <li><a href="{{ url('/about') }}" class="flex items-center min-h-touch px-4 py-3 text-base text-gray-700 hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">О нас</a></li>
                    <li><a href="{{ url('/delivery') }}" class="flex items-center min-h-touch px-4 py-3 text-base text-gray-700 hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Доставка</a></li>
                    <li><a href="{{ url('/warranty') }}" class="flex items-center min-h-touch px-4 py-3 text-base text-gray-700 hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Гарантия</a></li>
                    <li><a href="{{ url('/contacts') }}" class="flex items-center min-h-touch px-4 py-3 text-base text-gray-700 hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Контакты</a></li>
                    <li><a href="{{ url('/blog') }}" class="flex items-center min-h-touch px-4 py-3 text-base text-gray-700 hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Блог</a></li>
                    <li><a href="{{ url('/reviews') }}" class="flex items-center min-h-touch px-4 py-3 text-base text-gray-700 hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Отзывы</a></li>
                </ul>
            </nav>
        </div>
    </header>

    {{-- ОСНОВНОЙ КОНТЕНТ. Дочерний шаблон задаёт ровно одну <h1> через @yield('h1') (Req 23.3). --}}
    <main class="flex-1">
        <div class="mx-auto max-w-screen-2xl px-4 sm:px-6 lg:px-8 py-6 md:py-8">
            @hasSection('h1')
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-4 md:mb-6">
                    @yield('h1')
                </h1>
            @endif

            @yield('content')

            {{ $slot ?? '' }}
        </div>
    </main>

    {{-- ПОДВАЛ --}}
    <footer class="bg-gray-900 text-gray-200 mt-auto">
        <div class="mx-auto max-w-screen-2xl px-4 sm:px-6 lg:px-8 py-8 md:py-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <div>
                    <p class="text-lg font-semibold text-white">Сантехника</p>
                    <p class="mt-2 text-sm text-gray-400">
                        Интернет-магазин сантехники для дома и офиса.
                    </p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">Контакты</p>
                    <ul class="mt-2 space-y-1 text-sm text-gray-300">
                        <li>Телефон: <a href="tel:+70000000000" class="inline-flex items-center min-h-touch hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-400 rounded">+7&nbsp;000&nbsp;000-00-00</a></li>
                        <li>Email: <a href="mailto:info@example.com" class="inline-flex items-center min-h-touch hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-400 rounded">info@example.com</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">Информация</p>
                    <ul class="mt-2 space-y-1 text-sm text-gray-300">
                        <li><a href="{{ url('/about') }}" class="inline-flex items-center min-h-touch hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-400 rounded">О нас</a></li>
                        <li><a href="{{ url('/delivery') }}" class="inline-flex items-center min-h-touch hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-400 rounded">Доставка и оплата</a></li>
                        <li><a href="{{ url('/warranty') }}" class="inline-flex items-center min-h-touch hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-400 rounded">Гарантия и возврат</a></li>
                    </ul>
                </div>
            </div>
            <p class="mt-8 text-xs text-gray-500 border-t border-gray-800 pt-6">
                &copy; {{ date('Y') }} Сантехника. Все права защищены.
            </p>
        </div>
    </footer>

    {{-- Плавающая кнопка «Консультация / Обратный звонок» (Req 10.1).
         Заглушка: фиксирована справа внизу, размер ≥ 44×44 px. --}}
    <button type="button"
            class="fixed bottom-4 right-4 md:bottom-6 md:right-6 z-40 inline-flex items-center justify-center gap-2 min-h-touch min-w-touch px-4 py-3 rounded-full bg-blue-600 text-white text-sm md:text-base font-semibold shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            aria-label="Заказать обратный звонок">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2.5a1 1 0 01.95.68l1.2 3.6a1 1 0 01-.27 1.06L7.5 9.5a11 11 0 005 5l1.16-1.88a1 1 0 011.06-.27l3.6 1.2a1 1 0 01.68.95V17a2 2 0 01-2 2A14 14 0 013 5z"/>
        </svg>
        <span class="hidden sm:inline">Консультация</span>
    </button>

    {{-- Слот для скриптов отдельных страниц --}}
    @stack('scripts')
</body>
</html>
