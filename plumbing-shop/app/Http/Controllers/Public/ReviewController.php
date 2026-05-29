<?php

namespace App\Http\Controllers\Public;

class ReviewController extends BaseController
{
    public function index()
    {
        return view('public.reviews.index', [
            'reviews' => [
                ['author' => 'Анна К.', 'rating' => 5, 'text' => 'Заказали смеситель и раковину — доставили на следующий день, всё упаковано отлично.', 'product' => 'Смеситель Grohe Eurosmart', 'date' => '26.05.2026'],
                ['author' => 'Игорь М.', 'rating' => 4, 'text' => 'Хороший выбор унитазов, менеджер помог подобрать инсталляцию под нашу ванную.', 'product' => 'Унитаз Roca Gap', 'date' => '20.05.2026'],
                ['author' => 'Елена С.', 'rating' => 5, 'text' => 'Покупали душевую кабину — цена ниже, чем в соседних магазинах, монтаж прошёл без проблем.', 'product' => 'Душевая кабина Timo', 'date' => '12.05.2026'],
                ['author' => 'Дмитрий В.', 'rating' => 5, 'text' => 'Удобный сайт, понятные условия доставки по Москве. Рекомендую.', 'product' => 'Раковина Ideal Standard', 'date' => '05.05.2026'],
            ],
            'averageRating' => 4.8,
            'totalReviews' => 127,
        ]);
    }
}
