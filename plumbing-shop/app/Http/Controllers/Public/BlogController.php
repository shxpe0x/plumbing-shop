<?php

namespace App\Http\Controllers\Public;

class BlogController extends BaseController
{
    public function index()
    {
        return view('public.blog.index', [
            'articles' => [
                [
                    'title' => 'Как выбрать смеситель для кухни',
                    'excerpt' => 'Разбираем типы смесителей, материалы корпуса и ключевые характеристики для ежедневного использования.',
                    'date' => '28.05.2026',
                    'slug' => 'kak-vybrat-smesitel',
                ],
                [
                    'title' => '5 ошибок при установке унитаза',
                    'excerpt' => 'Что учесть при монтаже напольной и подвесной модели, чтобы избежать протечек и перекосов.',
                    'date' => '22.05.2026',
                    'slug' => 'oshibki-ustanovki-unitaza',
                ],
                [
                    'title' => 'Обзор душевых кабин 2026 года',
                    'excerpt' => 'Сравниваем популярные модели по габаритам, стеклу, гидромассажу и удобству ухода.',
                    'date' => '15.05.2026',
                    'slug' => 'obzor-dushevyh-kabin',
                ],
            ],
        ]);
    }
}
