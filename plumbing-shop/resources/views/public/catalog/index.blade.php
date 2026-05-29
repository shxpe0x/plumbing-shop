@extends('layouts.public')

@section('title', 'Каталог — Сантехника')
@section('description', 'Каталог сантехники: смесители, унитазы, ванны, душевые кабины и аксессуары.')

@section('h1', 'Каталог товаров')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 md:gap-8">
        {{-- Sidebar categories --}}
        <aside class="lg:col-span-1">
            <div class="rounded-xl bg-white shadow-sm border border-gray-100 p-4 md:p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Категории</h2>
                <ul class="space-y-1">
                    @foreach ($categories as $category)
                        <li>
                            <a href="#"
                               class="flex items-center justify-between min-h-touch px-3 py-2 rounded-md text-sm md:text-base text-gray-700 hover:bg-gray-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <span>{{ $category['name'] }}</span>
                                <span class="text-xs text-gray-400">{{ $category['count'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        {{-- Product grid --}}
        <div class="lg:col-span-3">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                <p class="text-gray-600 text-sm">Найдено товаров: <span class="font-semibold text-gray-900">{{ count($products) }}</span></p>
                <select class="rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 min-h-touch">
                    <option>По популярности</option>
                    <option>Сначала дешевле</option>
                    <option>Сначала дороже</option>
                    <option>По названию</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6">
                @foreach ($products as $product)
                    <article class="rounded-xl bg-white shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                        <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                            Фото товара
                        </div>
                        <div class="p-4">
                            <p class="text-xs text-gray-400 mb-1">Арт. {{ $product['sku'] }}</p>
                            <h2 class="font-semibold text-gray-900 mb-2">{{ $product['name'] }}</h2>
                            <p class="text-lg font-bold text-blue-700 mb-3">{{ $product['price'] }}</p>
                            <div class="flex items-center justify-between">
                                @if ($product['in_stock'])
                                    <span class="text-xs font-medium text-green-700 bg-green-50 px-2 py-1 rounded">В наличии</span>
                                @else
                                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">Под заказ</span>
                                @endif
                                <button type="button"
                                        class="inline-flex items-center min-h-touch px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    В корзину
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
@endsection
