<?php

use App\Http\Middleware\LogMissingTranslations;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Маршруты административной панели подключаются с префиксом /admin
            // и middleware-группой web. Auth/role-middleware будут добавлены
            // в задачах 3.7 и 12.1.
            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Регистрируем обработчик отсутствующих переводов на каждом
        // web-запросе — это даёт нам контекст текущего URL запроса в логах
        // (Req 26.4).
        $middleware->appendToGroup('web', LogMissingTranslations::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
