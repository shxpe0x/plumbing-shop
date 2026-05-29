@extends('layouts.public')

@section('title', 'Главная — Сантехника')
@section('description', 'Интернет-магазин сантехники: смесители, унитазы, душевые системы и аксессуары с доставкой по России.')

@section('h1', 'Интернет-магазин сантехники')

@section('content')
    {{-- Hero --}}
    <section class="rounded-2xl bg-gradient-to-r from-blue-700 to-blue-900 text-white px-6 py-10 md:px-12 md:py-16 mb-8 md:mb-12">
        <p class="text-blue-200 text-sm md:text-base font-medium mb-2">Доставка по всей России</p>
        <h2 class="text-2xl md:text-4xl font-bold mb-4">Качественная сантехника для дома и офиса</h2>
        <p class="text-blue-100 text-base md:text-lg max-w-2xl mb-6">
            Смесители, унитазы, ванны, душевые кабины и аксессуары от проверенных брендов.
            Гарантия, консультация специалиста и удобная доставка.
        </p>
        <a href="{{ route('catalog') }}"
           class="inline-flex items-center min-h-touch px-6 py-3 rounded-lg bg-white text-blue-700 font-semibold hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
            Перейти в каталог
        </a>
    </section>

    {{-- Categories --}}
    <section class="mb-10 md:mb-14">
        <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 md:mb-6">Популярные категории</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4">
            @foreach ($categories as $category)
                <a href="{{ route('catalog') }}"
                   class="flex flex-col items-center justify-center gap-2 p-4 md:p-6 rounded-xl bg-white shadow-sm border border-gray-100 hover:border-blue-300 hover:shadow-md transition min-h-touch focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span class="text-3xl md:text-4xl" aria-hidden="true">{{ $category['icon'] }}</span>
                    <span class="text-sm md:text-base font-medium text-gray-800 text-center">{{ $category['name'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Featured products --}}
    <section class="mb-10 md:mb-14">
        <div class="flex items-center justify-between mb-4 md:mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-900">Рекомендуем</h2>
            <a href="{{ route('catalog') }}" class="text-sm md:text-base text-blue-600 hover:text-blue-800 font-medium min-h-touch inline-flex items-center">Все товары →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach ($featured as $product)
                <article class="rounded-xl bg-white shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                    <div class="aspect-square bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                        Фото товара
                    </div>
                    <div class="p-4">
                        @if ($product['badge'])
                            <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700 mb-2">{{ $product['badge'] }}</span>
                        @endif
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $product['name'] }}</h3>
                        <p class="text-lg font-bold text-blue-700">{{ $product['price'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Info blocks --}}
    <section class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-2">Доставка и оплата</h2>
            <p class="text-gray-600 text-sm mb-3">Курьер по Москве, доставка по России, самовывоз из пункта выдачи.</p>
            <a href="{{ route('delivery') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Подробнее →</a>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-2">Гарантия и возврат</h2>
            <p class="text-gray-600 text-sm mb-3">Официальная гарантия производителя, возврат в течение 14 дней.</p>
            <a href="{{ route('warranty') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Подробнее →</a>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-2">Отзывы покупателей</h2>
            <p class="text-gray-600 text-sm mb-3">Более 120 отзывов от реальных клиентов. Средняя оценка — 4,8 из 5.</p>
            <a href="{{ route('reviews') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Читать отзывы →</a>
        </div>
    </section>
@endsection
