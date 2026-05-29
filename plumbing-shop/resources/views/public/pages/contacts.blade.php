@extends('layouts.public')

@section('title', 'Контакты — Сантехника')
@section('description', 'Контактная информация интернет-магазина «Сантехника».')

@section('h1', 'Контакты')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
        <div class="space-y-6">
            <section class="rounded-xl bg-white p-6 shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Связаться с нами</h2>
                <ul class="space-y-3 text-base text-gray-700">
                    <li class="flex items-start gap-3">
                        <span class="text-gray-400 shrink-0">📞</span>
                        <div>
                            <p class="text-sm text-gray-500">Телефон</p>
                            <a href="tel:+70000000000" class="font-medium text-blue-600 hover:text-blue-800">+7 (000) 000-00-00</a>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-gray-400 shrink-0">✉️</span>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <a href="mailto:info@example.com" class="font-medium text-blue-600 hover:text-blue-800">info@example.com</a>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-gray-400 shrink-0">📍</span>
                        <div>
                            <p class="text-sm text-gray-500">Адрес</p>
                            <p class="font-medium">г. Москва, ул. Примерная, д. 1</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-gray-400 shrink-0">🕐</span>
                        <div>
                            <p class="text-sm text-gray-500">Режим работы</p>
                            <p class="font-medium">Пн–Пт: 9:00–20:00, Сб–Вс: 10:00–18:00</p>
                        </div>
                    </li>
                </ul>
            </section>
        </div>

        <section class="rounded-xl bg-white p-6 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Напишите нам</h2>
            <form class="space-y-4" action="#" method="POST">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Ваше имя</label>
                    <input type="text" id="name" name="name" required
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 min-h-touch">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" required
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 min-h-touch">
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Сообщение</label>
                    <textarea id="message" name="message" rows="4" required
                              class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                <button type="submit"
                        class="inline-flex items-center min-h-touch px-6 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Отправить
                </button>
            </form>
        </section>
    </div>
@endsection
