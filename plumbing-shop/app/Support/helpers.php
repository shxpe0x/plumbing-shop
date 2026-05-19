<?php

declare(strict_types=1);

use App\Support\Money;

if (! function_exists('format_price')) {
    /**
     * Форматирует Money как «1 234,56 ₽».
     *
     * Правила формата:
     *  - целая часть числа разделена на тройки разрядов неразрывным пробелом (U+00A0);
     *  - десятичный разделитель — запятая;
     *  - ровно две цифры после запятой;
     *  - в конце — неразрывный пробел и символ ₽.
     */
    function format_price(Money $money): string
    {
        $kopecks = $money->kopecks;
        $rubles = intdiv($kopecks, 100);
        $remainder = $kopecks % 100;

        // Разделитель тысяч — неразрывный пробел U+00A0.
        $integerPart = number_format($rubles, 0, ',', "\u{00A0}");
        $fractionPart = str_pad((string) $remainder, 2, '0', STR_PAD_LEFT);

        return $integerPart.','.$fractionPart."\u{00A0}₽";
    }
}

if (! function_exists('parse_price')) {
    /**
     * Парсит строку формата format_price() обратно в копейки.
     *
     * Толерантно относится к обычным и неразрывным пробелам, символу ₽ и
     * как точке, так и запятой в качестве десятичного разделителя.
     * Используется для round-trip property-теста (parse_price(format_price(m)) === m).
     */
    function parse_price(string $value): int
    {
        // Удаляем символ рубля и любые виды пробелов (обычный, неразрывный, узкий неразрывный).
        $cleaned = str_replace(
            ['₽', ' ', "\u{00A0}", "\u{202F}", "\t"],
            '',
            $value
        );

        // Нормализуем десятичный разделитель к точке.
        $cleaned = str_replace(',', '.', $cleaned);

        if ($cleaned === '' || ! is_numeric($cleaned)) {
            throw new InvalidArgumentException(
                "Невалидная строка цены: '{$value}'"
            );
        }

        $rubles = (float) $cleaned;

        if ($rubles < 0) {
            throw new InvalidArgumentException(
                "Цена не может быть отрицательной: '{$value}'"
            );
        }

        return (int) round($rubles * 100);
    }
}
