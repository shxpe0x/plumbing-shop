@extends('layouts.public')

@section('title', 'О нас — Сантехника')
@section('description', 'О компании «Сантехника» — интернет-магазин сантехники с доставкой по России.')

@section('h1', 'О нас')

@section('content')
    <div class="max-w-3xl space-y-6 text-gray-700">
        <p class="text-base md:text-lg leading-relaxed">
            «Сантехника» — интернет-магазин, специализирующийся на продаже качественной сантехники
            для частных домов, квартир, офисов и коммерческих объектов. Мы работаем на рынке с 2018 года
            и помогаем клиентам подобрать оборудование под любой бюджет и задачу.
        </p>

        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-3">Наши преимущества</h2>
            <ul class="space-y-2 list-disc list-inside text-base">
                <li>Широкий ассортимент — более 500 наименований от ведущих брендов</li>
                <li>Консультация специалистов при выборе и комплектации</li>
                <li>Официальная гарантия производителя на всю продукцию</li>
                <li>Доставка по Москве и всей России</li>
                <li>Удобные способы оплаты: наличные, карта, банковский перевод</li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-3">Наша миссия</h2>
            <p class="text-base leading-relaxed">
                Мы стремимся сделать покупку сантехники простой и прозрачной: понятные цены,
                честные условия доставки и поддержка на каждом этапе — от выбора до установки.
            </p>
        </section>

        <section class="rounded-xl bg-white p-6 shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-3">Реквизиты</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <div><dt class="text-gray-500">Наименование</dt><dd class="font-medium">ООО «Сантехника»</dd></div>
                <div><dt class="text-gray-500">ИНН</dt><dd class="font-medium">7701234567</dd></div>
                <div><dt class="text-gray-500">Адрес</dt><dd class="font-medium">г. Москва, ул. Примерная, д. 1</dd></div>
                <div><dt class="text-gray-500">Телефон</dt><dd class="font-medium">+7 (000) 000-00-00</dd></div>
            </dl>
        </section>
    </div>
@endsection
