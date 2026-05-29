@extends('layouts.public')

@section('title', 'Доставка и оплата — Сантехника')
@section('description', 'Условия доставки и оплаты в интернет-магазине «Сантехника».')

@section('h1', 'Доставка и оплата')

@section('content')
    <div class="max-w-3xl space-y-8 text-gray-700">
        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Способы доставки</h2>
            <div class="space-y-4">
                <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-100">
                    <h3 class="font-semibold text-gray-900 mb-2">Курьер по Москве</h3>
                    <p class="text-sm md:text-base">Доставка на следующий день при заказе до 14:00. Стоимость — от 490 ₽, бесплатно при заказе от 15 000 ₽.</p>
                </div>
                <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-100">
                    <h3 class="font-semibold text-gray-900 mb-2">Доставка по России</h3>
                    <p class="text-sm md:text-base">Отправка транспортными компаниями (СДЭК, ПЭК, Деловые Линии). Срок — 2–7 рабочих дней в зависимости от региона.</p>
                </div>
                <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-100">
                    <h3 class="font-semibold text-gray-900 mb-2">Самовывоз</h3>
                    <p class="text-sm md:text-base">Бесплатный самовывоз из пункта выдачи в Москве. Заказ готов к выдаче в течение 1–2 рабочих дней.</p>
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Способы оплаты</h2>
            <ul class="space-y-2 list-disc list-inside text-base">
                <li>Наличными курьеру при получении</li>
                <li>Банковской картой при получении</li>
                <li>Банковский перевод по счёту (для юридических лиц)</li>
            </ul>
            <p class="mt-4 text-sm text-gray-500">Онлайн-оплата на сайте будет доступна в следующих версиях проекта.</p>
        </section>
    </div>
@endsection
