@extends('layouts.admin')

@section('title', 'Админ-панель — Дашборд')
@section('h1', 'Дашборд')

@section('content')
    {{-- Stats cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6 mb-8">
        @foreach ($stats as $stat)
            <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-200">
                <p class="text-sm text-gray-500 mb-1">{{ $stat['label'] }}</p>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-bold text-gray-900">{{ $stat['value'] }}</p>
                    @if ($stat['change'] !== '0')
                        <span class="text-sm font-medium {{ str_starts_with($stat['change'], '+') ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $stat['change'] }}
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Recent orders --}}
    <section class="rounded-xl bg-white shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">Последние заказы</h2>
            <a href="{{ url('/admin/orders') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Все заказы →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">№ заказа</th>
                        <th class="px-5 py-3 text-left font-medium">Клиент</th>
                        <th class="px-5 py-3 text-left font-medium">Сумма</th>
                        <th class="px-5 py-3 text-left font-medium">Статус</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($recentOrders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium text-gray-900">#{{ $order['id'] }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $order['customer'] }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $order['total'] }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium
                                    @if ($order['status'] === 'Новый') bg-blue-100 text-blue-700
                                    @elseif ($order['status'] === 'В доставке') bg-yellow-100 text-yellow-700
                                    @else bg-green-100 text-green-700 @endif">
                                    {{ $order['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
