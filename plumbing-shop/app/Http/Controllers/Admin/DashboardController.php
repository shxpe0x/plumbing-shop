<?php

namespace App\Http\Controllers\Admin;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'Заказы сегодня', 'value' => '12', 'change' => '+3'],
                ['label' => 'Товаров в каталоге', 'value' => '486', 'change' => '+8'],
                ['label' => 'Новых отзывов', 'value' => '5', 'change' => '+2'],
                ['label' => 'Обратных звонков', 'value' => '3', 'change' => '0'],
            ],
            'recentOrders' => [
                ['id' => '1042', 'customer' => 'Петров А.В.', 'total' => '24 680,00 ₽', 'status' => 'Новый'],
                ['id' => '1041', 'customer' => 'Сидорова М.И.', 'total' => '8 450,00 ₽', 'status' => 'В доставке'],
                ['id' => '1040', 'customer' => 'Козлов Д.С.', 'total' => '51 200,00 ₽', 'status' => 'Выполнен'],
            ],
        ]);
    }
}
