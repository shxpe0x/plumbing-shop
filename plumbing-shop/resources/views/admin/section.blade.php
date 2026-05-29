@extends('layouts.admin')

@section('title', $title . ' — Админ-панель')
@section('h1', $h1)

@section('content')
    <div class="rounded-xl bg-white shadow-sm border border-gray-200 p-6 md:p-8">
        <p class="text-gray-700 text-base mb-6">{{ $description }}</p>

        <div class="rounded-lg bg-gray-50 border border-dashed border-gray-300 p-8 text-center">
            <p class="text-gray-500 text-sm md:text-base">
                Раздел «{{ $title }}» готов к наполнению.
                Полный CRUD будет реализован в следующих задачах спецификации.
            </p>
            <button type="button"
                    class="mt-4 inline-flex items-center min-h-touch px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                + Добавить
            </button>
        </div>
    </div>
@endsection
