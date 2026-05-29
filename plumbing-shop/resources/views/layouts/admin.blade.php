{{-- Layout административной панели.
     Семантика: <aside> (боковое меню), <header> (верхняя панель админа), <main> (контент).
     Ровно одна <h1> на странице через @yield('h1') (Req 23.3).
     Адаптивность 320–1920 px (Req 25.1).
     На мобильных (< md) боковое меню сворачивается через Alpine.js (Req 25.3).
     Минимальный размер touch-цели 44×44 px (Req 25.6).
--}}
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Административная панель')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('meta')
</head>
<body class="font-sans antialiased min-h-screen bg-gray-100 text-gray-900"
      x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">

        {{-- БОКОВОЕ МЕНЮ. На мобильных скрыто за оверлеем, на md+ всегда видно. --}}
        <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-gray-900 text-gray-100 transform md:relative md:translate-x-0 md:flex-shrink-0 transition-transform duration-200 ease-in-out"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               aria-label="Боковое меню админ-панели">
            <div class="flex items-center justify-between h-16 px-4 border-b border-gray-800">
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center min-h-touch text-lg font-bold text-white hover:text-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">
                    Админ-панель
                </a>
                {{-- Кнопка закрытия на мобильных --}}
                <button type="button"
                        class="md:hidden inline-flex items-center justify-center min-h-touch min-w-touch p-2 rounded-md text-gray-300 hover:text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        @click="sidebarOpen = false"
                        aria-label="Закрыть меню">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <nav class="px-2 py-4 overflow-y-auto" aria-label="Разделы админ-панели">
                <ul class="space-y-1">
                    <li><a href="{{ route('admin.dashboard') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Дашборд</a></li>
                    <li><a href="{{ url('/admin/products') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Товары</a></li>
                    <li><a href="{{ url('/admin/categories') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Категории</a></li>
                    <li><a href="{{ url('/admin/orders') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Заказы</a></li>
                    <li><a href="{{ url('/admin/promotions') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Акции</a></li>
                    <li><a href="{{ url('/admin/banners') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Баннеры</a></li>
                    <li><a href="{{ url('/admin/pages') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Страницы</a></li>
                    <li><a href="{{ url('/admin/articles') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Статьи</a></li>
                    <li><a href="{{ url('/admin/reviews') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Отзывы</a></li>
                    <li><a href="{{ url('/admin/users') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Пользователи</a></li>
                    <li><a href="{{ url('/admin/settings') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Настройки</a></li>
                    <li><a href="{{ url('/admin/delivery-methods') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Способы доставки</a></li>
                    <li><a href="{{ url('/admin/payment-methods') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Способы оплаты</a></li>
                    <li><a href="{{ url('/admin/regions') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Регионы</a></li>
                    <li><a href="{{ url('/admin/tariffs') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Тарифы</a></li>
                    <li><a href="{{ url('/admin/callbacks') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Обратные звонки</a></li>
                    <li><a href="{{ url('/admin/chat') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Чат</a></li>
                    <li><a href="{{ url('/admin/reports') }}" class="flex items-center min-h-touch px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">Отчёты</a></li>
                </ul>
            </nav>
        </aside>

        {{-- Затемнение под мобильным меню --}}
        <div x-show="sidebarOpen"
             x-cloak
             x-transition.opacity
             class="fixed inset-0 z-30 bg-black/50 md:hidden"
             @click="sidebarOpen = false"
             aria-hidden="true"></div>

        {{-- ПРАВАЯ КОЛОНКА: верхняя панель + контент --}}
        <div class="flex-1 flex flex-col min-w-0">

            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3">
                        {{-- Кнопка-«гамбургер» для мобильных --}}
                        <button type="button"
                                class="md:hidden inline-flex items-center justify-center min-h-touch min-w-touch p-2 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                @click="sidebarOpen = ! sidebarOpen"
                                :aria-expanded="sidebarOpen.toString()"
                                aria-controls="admin-sidebar"
                                aria-label="Открыть меню">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <p class="text-sm md:text-base text-gray-700">
                            Здравствуйте, <span class="font-semibold">{{ auth()->user()->name ?? 'администратор' }}</span>
                        </p>
                    </div>

                    <form method="POST" action="{{ url('/logout') }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center min-h-touch min-w-touch px-4 py-2 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Выйти
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6 md:py-8">
                @hasSection('h1')
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 md:mb-6">
                        @yield('h1')
                    </h1>
                @endif

                @yield('content')

                {{ $slot ?? '' }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
