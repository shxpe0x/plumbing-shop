<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\Money;
use PHPUnit\Framework\TestCase;

final class PriceFormatterTest extends TestCase
{
    public function test_format_price_zero(): void
    {
        $this->assertSame(
            "0,00\u{00A0}₽",
            format_price(Money::fromKopecks(0))
        );
    }

    public function test_format_price_one_ruble(): void
    {
        $this->assertSame(
            "1,00\u{00A0}₽",
            format_price(Money::fromKopecks(100))
        );
    }

    public function test_format_price_with_thousands_separator(): void
    {
        $this->assertSame(
            "1\u{00A0}234,56\u{00A0}₽",
            format_price(Money::fromKopecks(123456))
        );
    }

    public function test_format_price_large_amount(): void
    {
        $this->assertSame(
            "999\u{00A0}999,99\u{00A0}₽",
            format_price(Money::fromKopecks(99999999))
        );
    }

    public function test_format_price_pads_kopecks_with_leading_zero(): void
    {
        // 100 руб. 05 коп. = 10005 коп.
        $this->assertSame(
            "100,05\u{00A0}₽",
            format_price(Money::fromKopecks(10005))
        );
    }

    public function test_parse_price_round_trip_for_large_amount(): void
    {
        $kopecks = 987654321;

        $this->assertSame(
            $kopecks,
            parse_price(format_price(Money::fromKopecks($kopecks)))
        );
    }

    public function test_parse_price_round_trip_for_zero(): void
    {
        $this->assertSame(
            0,
            parse_price(format_price(Money::fromKopecks(0)))
        );
    }

    public function test_parse_price_round_trip_for_small_amount(): void
    {
        $this->assertSame(
            100,
            parse_price(format_price(Money::fromKopecks(100)))
        );
    }

    public function test_parse_price_accepts_dot_as_decimal_separator(): void
    {
        $this->assertSame(12345, parse_price('123.45 ₽'));
    }

    public function test_parse_price_accepts_comma_as_decimal_separator(): void
    {
        $this->assertSame(12345, parse_price("123,45\u{00A0}₽"));
    }
}
