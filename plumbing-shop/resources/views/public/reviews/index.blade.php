@extends('layouts.public')

@section('title', 'Отзывы — Сантехника')
@section('description', 'Отзывы покупателей интернет-магазина «Сантехника».')

@section('h1', 'Отзывы покупателей')

@section('content')
    <div class="mb-8 rounded-xl bg-white p-6 shadow-sm border border-gray-100 flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="text-center sm:text-left">
            <p class="text-4xl font-bold text-blue-700">{{ number_format($averageRating, 1, ',', '') }}</p>
            <p class="text-sm text-gray-500">из 5 звёзд</p>
        </div>
        <div class="flex-1">
            <div class="flex gap-1 mb-1" aria-label="Средняя оценка {{ $averageRating }} из 5">
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="h-5 w-5 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
            </div>
            <p class="text-sm text-gray-600">На основе {{ $totalReviews }} отзывов</p>
        </div>
        <button type="button"
                class="inline-flex items-center justify-center min-h-touch px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shrink-0">
            Оставить отзыв
        </button>
    </div>

    <div class="space-y-4 md:space-y-6">
        @foreach ($reviews as $review)
            <article class="rounded-xl bg-white p-5 md:p-6 shadow-sm border border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $review['author'] }}</p>
                        <p class="text-xs text-gray-400">{{ $review['date'] }}</p>
                    </div>
                    <div class="flex gap-0.5" aria-label="Оценка {{ $review['rating'] }} из 5">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="h-4 w-4 {{ $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                </div>
                <p class="text-gray-700 text-base mb-2">{{ $review['text'] }}</p>
                <p class="text-sm text-gray-400">Товар: {{ $review['product'] }}</p>
            </article>
        @endforeach
    </div>
@endsection
