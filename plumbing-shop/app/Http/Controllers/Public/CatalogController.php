<?php

namespace App\Http\Controllers\Public;

class CatalogController extends BaseController
{
    public function index()
    {
        return view('public.catalog.index', [
            'categories' => [
                ['name' => 'Смесители', 'count' => 128],
                ['name' => 'Унитазы и биде', 'count' => 94],
                ['name' => 'Ванны', 'count' => 56],
                ['name' => 'Душевые системы', 'count' => 73],
                ['name' => 'Раковины', 'count' => 61],
                ['name' => 'Мебель для ванной', 'count' => 42],
                ['name' => 'Полотенцесушители', 'count' => 38],
                ['name' => 'Инсталляции', 'count' => 29],
            ],
            'products' => [
                ['name' => 'Смеситель для кухни Hansgrohe Focus', 'sku' => 'HG-3173', 'price' => '9 870,00 ₽', 'in_stock' => true],
                ['name' => 'Унитаз подвесной Cersanit City', 'sku' => 'CS-4412', 'price' => '14 200,00 ₽', 'in_stock' => true],
                ['name' => 'Ванна акриловая Ravak 170 см', 'sku' => 'RV-9011', 'price' => '32 500,00 ₽', 'in_stock' => false],
                ['name' => 'Душевой гарнитур Oras Nova', 'sku' => 'OR-2280', 'price' => '21 990,00 ₽', 'in_stock' => true],
                ['name' => 'Раковина накладная Villeroy & Boch', 'sku' => 'VB-5501', 'price' => '11 450,00 ₽', 'in_stock' => true],
                ['name' => 'Полотенцесушитель электрический', 'sku' => 'PT-7744', 'price' => '7 890,00 ₽', 'in_stock' => true],
            ],
        ]);
    }
}
