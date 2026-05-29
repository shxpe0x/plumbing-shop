<?php

namespace App\Http\Controllers\Public;

class HomeController extends BaseController
{
    public function index()
    {
        return view('public.home.index', [
            'categories' => [
                ['name' => 'Смесители', 'icon' => '🚿'],
                ['name' => 'Унитазы', 'icon' => '🚽'],
                ['name' => 'Ванны', 'icon' => '🛁'],
                ['name' => 'Душевые кабины', 'icon' => '🚿'],
                ['name' => 'Раковины', 'icon' => '🚰'],
                ['name' => 'Полотенцесушители', 'icon' => '♨️'],
            ],
            'featured' => [
                ['name' => 'Смеситель Grohe Eurosmart', 'price' => '12 490,00 ₽', 'badge' => 'Хит'],
                ['name' => 'Унитаз Roca Gap', 'price' => '18 750,00 ₽', 'badge' => 'Новинка'],
                ['name' => 'Душевая кабина Timo', 'price' => '45 900,00 ₽', 'badge' => null],
                ['name' => 'Раковина Ideal Standard', 'price' => '6 320,00 ₽', 'badge' => 'Акция'],
            ],
        ]);
    }
}
