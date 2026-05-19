<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\Money;
use DomainException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    public function test_from_rubles_with_dot_decimal_separator(): void
    {
        $money = Money::fromRubles('123.45');

        $this->assertSame(12345, $money->kopecks);
    }

    public function test_from_rubles_with_comma_decimal_separator(): void
    {
        $money = Money::fromRubles('123,45');

        $this->assertSame(12345, $money->kopecks);
    }

    public function test_from_rubles_with_float_input(): void
    {
        $money = Money::fromRubles(99.99);

        $this->assertSame(9999, $money->kopecks);
    }

    public function test_from_kopecks_factory(): void
    {
        $money = Money::fromKopecks(500);

        $this->assertSame(500, $money->kopecks);
    }

    public function test_plus_returns_new_instance_with_summed_kopecks(): void
    {
        $a = Money::fromKopecks(100);
        $b = Money::fromKopecks(200);

        $result = $a->plus($b);

        $this->assertSame(300, $result->kopecks);
        // Immutability: исходные объекты не изменены.
        $this->assertSame(100, $a->kopecks);
        $this->assertSame(200, $b->kopecks);
    }

    public function test_minus_returns_new_instance_with_difference(): void
    {
        $result = Money::fromKopecks(500)->minus(Money::fromKopecks(200));

        $this->assertSame(300, $result->kopecks);
    }

    public function test_multiplied_by_integer(): void
    {
        $result = Money::fromKopecks(100)->multipliedBy(3);

        $this->assertSame(300, $result->kopecks);
    }

    public function test_minus_throws_when_result_is_negative(): void
    {
        $this->expectException(DomainException::class);

        Money::fromKopecks(100)->minus(Money::fromKopecks(200));
    }

    public function test_constructor_rejects_negative_kopecks(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Money(-1);
    }

    public function test_from_rubles_rejects_negative_value(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Money::fromRubles('-1.00');
    }

    public function test_from_rubles_rejects_non_numeric_string(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Money::fromRubles('abc');
    }

    public function test_multiplied_by_rejects_negative_multiplier(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Money::fromKopecks(100)->multipliedBy(-2);
    }

    public function test_format_zero_kopecks(): void
    {
        $formatted = Money::fromKopecks(0)->format();

        $this->assertStringContainsString(',00', $formatted);
        $this->assertStringEndsWith("\u{00A0}₽", $formatted);
    }

    public function test_equals_compares_kopecks(): void
    {
        $this->assertTrue(Money::fromKopecks(100)->equals(Money::fromKopecks(100)));
        $this->assertFalse(Money::fromKopecks(100)->equals(Money::fromKopecks(200)));
    }

    public function test_less_than_and_greater_than(): void
    {
        $cheap = Money::fromKopecks(100);
        $expensive = Money::fromKopecks(200);

        $this->assertTrue($cheap->lessThan($expensive));
        $this->assertTrue($expensive->greaterThan($cheap));
        $this->assertFalse($cheap->greaterThan($expensive));
        $this->assertFalse($expensive->lessThan($cheap));
    }
}
