@extends('layouts.public')

@section('title', 'Блог — Сантехника')
@section('description', 'Статьи и советы по выбору и установке сантехники.')

@section('h1', 'Блог')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
        @foreach ($articles as $article)
            <article class="rounded-xl bg-white shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col">
                <div class="aspect-video bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                    Обложка статьи
                </div>
                <div class="p-5 flex flex-col flex-1">
                    <time datetime="{{ $article['date'] }}" class="text-xs text-gray-400 mb-2">{{ $article['date'] }}</time>
                    <h2 class="text-lg font-bold text-gray-900 mb-2">{{ $article['title'] }}</h2>
                    <p class="text-sm text-gray-600 flex-1 mb-4">{{ $article['excerpt'] }}</p>
                    <a href="#"
                       class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 min-h-touch">
                        Читать далее →
                    </a>
                </div>
            </article>
        @endforeach
    </div>
@endsection
