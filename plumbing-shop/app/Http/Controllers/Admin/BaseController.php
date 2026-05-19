<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * Базовый контроллер административной панели.
 *
 * Все контроллеры из пространства имён App\Http\Controllers\Admin
 * наследуются от него. Здесь могут размещаться общие зависимости
 * (например, проверка ролей, аудит действий, хлебные крошки админа).
 *
 * Конкретные методы добавляются по мере реализации задач 12.x–14.x.
 */
abstract class BaseController extends Controller
{
}
