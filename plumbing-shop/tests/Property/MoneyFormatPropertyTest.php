<?php

declare(strict_types=1);

namespace Tests\Property;

use App\Support\Money;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Feature: plumbing-shop-website
 * Property 13: Round-trip формата денежных сумм.
 *
 * Validates: Requirements 26.2, 4.1, 5.2.
 *
 * Свойство (универсально квантифицированное):
 *   ∀ m ∈ [0; 999_999_999] копеек справедливо:
 *     1) format_price(Money::fromKopecks($m)) оканчивается на NBSP+₽;
 *     2) в строке ровно одна запятая; после неё ровно две цифры;
 *     3) если m ≥ 100_000 (целая часть ≥ 1000 ₽) — в целой части
 *        присутствует хотя бы один неразрывный пробел U+00A0
 *        как разделитель тысяч;
 *     4) parse_price(format_price(Money::fromKopecks($m))) === $m
 *        (round-trip: парсинг обратно даёт исходное число копеек).
 *
 * Запуск:
 *   php artisan test --filter=MoneyFormatPropertyTest
 *   php vendor/bin/phpunit --filter=MoneyFormatPropertyTest
 */
final class MoneyFormatPropertyTest extends TestCase
{
    use TestTrait;

    /**
     * Property 13: Round-trip формата денежных сумм.
     *
     * Validates: Requirements 26.2, 4.1, 5.2.
     */
    public function test_format_price_round_trip(): void
    {
        $this->limitTo(100)
            ->forAll(Generator\choose(0, 999_999_999))
            ->then(function (int $kopecks): void {
                $formatted = format_price(Money::fromKopecks($kopecks));

                // (1) Шаблон: строка оканчивается на NBSP + символ рубля.
                $this->assertStringEndsWith(
                    "\u{00A0}₽",
                    $formatted,
                    "Строка '$formatted' должна оканчиваться на NBSP+₽"
                );

                // Отделяем числовую часть от хвоста "<NBSP>₽".
                $numericPart = substr($formatted, 0, -strlen("\u{00A0}₽"));

                // (2) Ровно одна запятая, после неё ровно две цифры.
                $parts = explode(',', $numericPart);
                $this->assertCount(
                    2,
                    $parts,
                    "В строке '$formatted' должна быть ровно одна запятая"
                );
                $this->assertMatchesRegularExpression(
                    '/^\d{2}$/',
                    $parts[1],
                    "После запятой должно быть ровно две цифры (получено '{$parts[1]}')"
                );

                // (3) Если целая часть ≥ 1000 ₽ — должен быть хотя бы один
                // неразрывный пробел (разделитель тысяч).
                if ($kopecks >= 100_000) {
                    $this->assertStringContainsString(
                        "\u{00A0}",
                        $parts[0],
                        "В целой части '{$parts[0]}' (m=$kopecks коп.) ".
                        'ожидается NBSP-разделитель тысяч'
                    );
                }

                // (4) Round-trip: parse_price(format_price(m)) === m.
                $this->assertSame(
                    $kopecks,
                    parse_price($formatted),
                    "Round-trip нарушен для $kopecks коп. (форматировано: '$formatted')"
                );
            });
    }

    /**
     * Дополнительные инварианты формата (Property 13, расширение).
     *
     * Validates: Requirements 26.2, 4.1, 5.2.
     *
     * Проверяемые инварианты:
     *  - формат не содержит обычного ASCII-пробела (только U+00A0);
     *  - дробная часть всегда состоит ровно из двух цифр;
     *  - целая часть состоит только из цифр и неразрывных пробелов;
     *  - целая часть не начинается с неразрывного пробела;
     *  - количество цифр между неразрывными пробелами в целой части
     *    подчиняется правилу группировки по 3 (с возможной короткой
     *    головной группой 1–3).
     */
    public function test_format_price_format_invariants(): void
    {
        $this->limitTo(100)
            ->forAll(Generator\choose(0, 999_999_999))
            ->then(function (int $kopecks): void {
                $formatted = format_price(Money::fromKopecks($kopecks));

                // Обычных ASCII-пробелов в строке быть не должно — только U+00A0.
                $this->assertStringNotContainsString(
                    ' ',
                    $formatted,
                    "Формат '$formatted' не должен содержать ASCII-пробел"
                );

                $numericPart = substr($formatted, 0, -strlen("\u{00A0}₽"));
                [$integerPart, $fractionPart] = explode(',', $numericPart);

                // Дробная часть — ровно две цифры.
                $this->assertSame(
                    2,
                    strlen($fractionPart),
                    "Дробная часть '$fractionPart' должна быть ровно из 2 цифр"
                );
                $this->assertMatchesRegularExpression(
                    '/^\d{2}$/',
                    $fractionPart,
                    "Дробная часть '$fractionPart' должна состоять только из цифр"
                );

                // Целая часть состоит только из цифр и NBSP, не начинается с NBSP.
                $this->assertMatchesRegularExpression(
                    '/^\d+(\x{00A0}\d+)*$/u',
                    $integerPart,
                    "Целая часть '$integerPart' должна состоять только из цифр и ".
                    'NBSP-разделителей и не начинаться с NBSP'
                );

                // Каждая «хвостовая» группа (после первого NBSP) — ровно три цифры.
                $groups = explode("\u{00A0}", $integerPart);
                $headLength = strlen($groups[0]);
                $this->assertGreaterThanOrEqual(
                    1,
                    $headLength,
                    "Головная группа целой части '$integerPart' пуста"
                );
                $this->assertLessThanOrEqual(
                    3,
                    $headLength,
                    "Головная группа '{$groups[0]}' должна быть длиной 1–3 цифры"
                );
                for ($i = 1; $i < count($groups); $i++) {
                    $this->assertSame(
                        3,
                        strlen($groups[$i]),
                        "Хвостовая группа '{$groups[$i]}' должна быть ровно 3 цифры"
                    );
                }

                // Знак минус недопустим: Money гарантирует неотрицательность.
                $this->assertStringNotContainsString(
                    '-',
                    $formatted,
                    "Формат '$formatted' не должен содержать знак минус"
                );
            });
    }
}
