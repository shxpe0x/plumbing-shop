<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;

class SectionController extends BaseController
{
    /** @var array<string, array{title: string, h1: string, description: string}> */
    private const SECTIONS = [
        'products' => [
            'title' => 'Товары',
            'h1' => 'Управление товарами',
            'description' => 'Добавление, редактирование и публикация товаров каталога.',
        ],
        'categories' => [
            'title' => 'Категории',
            'h1' => 'Управление категориями',
            'description' => 'Дерево категорий, SEO-поля и порядок отображения.',
        ],
        'orders' => [
            'title' => 'Заказы',
            'h1' => 'Управление заказами',
            'description' => 'Список заказов, статусы, история изменений и печать документов.',
        ],
        'promotions' => [
            'title' => 'Акции',
            'h1' => 'Управление акциями',
            'description' => 'Создание акций, скидок и специальных предложений.',
        ],
        'banners' => [
            'title' => 'Баннеры',
            'h1' => 'Управление баннерами',
            'description' => 'Баннеры на главной странице и в разделах каталога.',
        ],
        'pages' => [
            'title' => 'Страницы',
            'h1' => 'Управление страницами',
            'description' => 'Редактирование информационных страниц сайта.',
        ],
        'articles' => [
            'title' => 'Статьи',
            'h1' => 'Управление статьями',
            'description' => 'Публикация и редактирование записей блога.',
        ],
        'reviews' => [
            'title' => 'Отзывы',
            'h1' => 'Модерация отзывов',
            'description' => 'Просмотр, одобрение и отклонение отзывов покупателей.',
        ],
        'users' => [
            'title' => 'Пользователи',
            'h1' => 'Управление пользователями',
            'description' => 'Учётные записи клиентов и сотрудников, роли и статусы.',
        ],
        'settings' => [
            'title' => 'Настройки',
            'h1' => 'Настройки магазина',
            'description' => 'Контакты, реквизиты, SEO и общие параметры сайта.',
        ],
        'delivery-methods' => [
            'title' => 'Способы доставки',
            'h1' => 'Способы доставки',
            'description' => 'Курьер, самовывоз, транспортные компании.',
        ],
        'payment-methods' => [
            'title' => 'Способы оплаты',
            'h1' => 'Способы оплаты',
            'description' => 'Наличные, карта при получении, банковский перевод.',
        ],
        'regions' => [
            'title' => 'Регионы',
            'h1' => 'Регионы доставки',
            'description' => 'География обслуживания и зоны доставки.',
        ],
        'tariffs' => [
            'title' => 'Тарифы',
            'h1' => 'Тарифы доставки',
            'description' => 'Стоимость доставки по регионам и весовым категориям.',
        ],
        'callbacks' => [
            'title' => 'Обратные звонки',
            'h1' => 'Заявки на обратный звонок',
            'description' => 'Входящие заявки с формы «Консультация».',
        ],
        'chat' => [
            'title' => 'Чат',
            'h1' => 'Онлайн-чат',
            'description' => 'Переписка с посетителями сайта в режиме реального времени.',
        ],
        'reports' => [
            'title' => 'Отчёты',
            'h1' => 'Отчёты и аналитика',
            'description' => 'Продажи, остатки, конверсия и экспорт данных.',
        ],
    ];

    public function show(string $section): View
    {
        abort_unless(isset(self::SECTIONS[$section]), 404);

        $meta = self::SECTIONS[$section];

        return view('admin.section', [
            'section' => $section,
            'title' => $meta['title'],
            'h1' => $meta['h1'],
            'description' => $meta['description'],
        ]);
    }
}
