<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

/**
 * Базовый контроллер публичной части сайта.
 *
 * Все контроллеры из пространства имён App\Http\Controllers\Public
 * наследуются от него. Здесь могут размещаться общие зависимости
 * (например, инжекция сервисов меню, хлебных крошек, SEO-мета).
 *
 * Конкретные методы добавляются по мере реализации задач 7.x–10.x.
 */
abstract class BaseController extends Controller
{
}
