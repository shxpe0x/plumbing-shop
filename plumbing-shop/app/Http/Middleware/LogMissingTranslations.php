<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Регистрирует запросы к отсутствующим переводам.
 *
 * Через `Lang::handleMissingKeysUsing()` пишет ключ + URL запроса в отдельный
 * лог-канал `missing_translations`. Сам ключ возвращается без префикса —
 * Laravel сам отдаст его пользователю при отсутствии перевода (Req 26.4).
 */
class LogMissingTranslations
{
    public function handle(Request $request, Closure $next): Response
    {
        $url = $request->fullUrl();
        $locale = app()->getLocale();

        Lang::handleMissingKeysUsing(static function (
            string $key,
            array $replacements,
            string $usedLocale,
            ?string $fallbackLocale = null,
        ) use ($url, $locale): ?string {
            try {
                Log::channel('missing_translations')->warning('Missing translation', [
                    'key' => $key,
                    'replacements' => $replacements,
                    'used_locale' => $usedLocale,
                    'fallback_locale' => $fallbackLocale,
                    'app_locale' => $locale,
                    'url' => $url,
                ]);
            } catch (\Throwable $e) {
                // Логирование никогда не должно ломать запрос.
            }

            // Возвращая null, мы оставляем стандартное поведение Laravel
            // (вернуть исходный ключ без префикса локали).
            return null;
        });

        return $next($request);
    }
}
