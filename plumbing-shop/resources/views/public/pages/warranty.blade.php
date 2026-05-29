@extends('layouts.public')

@section('title', 'Гарантия и возврат — Сантехника')
@section('description', 'Условия гарантии и возврата товаров в интернет-магазине «Сантехника».')

@section('h1', 'Гарантия и возврат')

@section('content')
    <div class="max-w-3xl space-y-8 text-gray-700">
        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Гарантия</h2>
            <p class="text-base leading-relaxed mb-4">
                На всю продукцию распространяется официальная гарантия производителя.
                Срок гарантии указан в карточке каждого товара и составляет от 1 до 5 лет
                в зависимости от бренда и категории.
            </p>
            <ul class="space-y-2 list-disc list-inside text-base">
                <li>Гарантийный талон и документы передаются при получении заказа</li>
                <li>Гарантийное обслуживание через авторизованные сервисные центры</li>
                <li>Помощь в оформлении гарантийного случая — по телефону или email</li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Возврат и обмен</h2>
            <p class="text-base leading-relaxed mb-4">
                Вы можете вернуть или обменять товар надлежащего качества в течение 14 дней
                с момента получения, если сохранён товарный вид, упаковка и документы.
            </p>
            <div class="rounded-xl bg-amber-50 border border-amber-200 p-5 text-sm md:text-base text-amber-900">
                <p class="font-semibold mb-2">Обратите внимание</p>
                <p>Товары, установленные или использованные, а также сантехника индивидуального назначения
                возврату не подлежат, за исключением случаев заводского брака.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Как оформить возврат</h2>
            <ol class="space-y-2 list-decimal list-inside text-base">
                <li>Свяжитесь с нами по телефону или через <a href="{{ route('contacts') }}" class="text-blue-600 hover:text-blue-800">форму обратной связи</a></li>
                <li>Укажите номер заказа и причину возврата</li>
                <li>Дождитесь подтверждения и инструкций по отправке товара</li>
                <li>После проверки товара средства будут возвращены в течение 10 рабочих дней</li>
            </ol>
        </section>
    </div>
@endsection
