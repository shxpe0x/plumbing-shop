<?php

declare(strict_types=1);

namespace App\Support;

use DomainException;
use InvalidArgumentException;

/**
 * Value Object для хранения денежной суммы в рублях (RUB).
 *
 * Хранение и все арифметические операции выполняются в копейках (целочисленный int),
 * чтобы избежать ошибок округления чисел с плавающей точкой. Форматирование
 * для пользовательского вывода производится только в методе {@see Money::format()}
 * либо через хелпер format_price().
 *
 * Класс final и неизменяем (immutable): все мутирующие методы возвращают новый
 * экземпляр.
 */
final class Money
{
    /**
     * @param int $kopecks Сумма в копейках. Должна быть неотрицательной.
     */
    public function __construct(public readonly int $kopecks)
    {
        if ($kopecks < 0) {
            throw new InvalidArgumentException(
                "Money не может быть отрицательной (получено {$kopecks} коп.)"
            );
        }
    }

    /**
     * Создать Money из количества рублей.
     *
     * Принимает строку «1234.56», «1234,56» (русская локаль) или float.
     * Конвертирует в копейки через (int) round($value * 100).
     * Допускаются только неотрицательные значения.
     */
    public static function fromRubles(string|float $rubles): self
    {
        if (is_string($rubles)) {
            $normalized = str_replace([',', ' ', "\u{00A0}"], ['.', '', ''], trim($rubles));

            if ($normalized === '' || ! is_numeric($normalized)) {
                throw new InvalidArgumentException(
                    "Невалидное значение рублей: '{$rubles}'"
                );
            }

            $value = (float) $normalized;
        } else {
            $value = $rubles;
        }

        if ($value < 0) {
            throw new InvalidArgumentException(
                "Money не может быть отрицательной (получено {$value} руб.)"
            );
        }

        return new self((int) round($value * 100));
    }

    /**
     * Альтернативная фабрика, более явная по смыслу, чем конструктор.
     */
    public static function fromKopecks(int $kopecks): self
    {
        return new self($kopecks);
    }

    /**
     * Сложение. Принимает только Money.
     */
    public function plus(Money $other): self
    {
        return new self($this->kopecks + $other->kopecks);
    }

    /**
     * Вычитание. Принимает только Money.
     *
     * Строгий вариант: если результат отрицательный, выбрасывается DomainException —
     * это безопаснее для денежных расчётов, чем молчаливо допускать долговые суммы.
     * Если когда-нибудь понадобятся отрицательные значения, добавим отдельный класс
     * SignedMoney или метод minusAllowingNegative().
     */
    public function minus(Money $other): self
    {
        $result = $this->kopecks - $other->kopecks;

        if ($result < 0) {
            throw new DomainException(
                "Результат вычитания отрицательный: {$this->kopecks} - {$other->kopecks} = {$result}"
            );
        }

        return new self($result);
    }

    /**
     * Умножение на целое количество (например, для расчёта стоимости позиции корзины).
     *
     * Принимает только int, чтобы избежать ошибок округления. Для процентных скидок
     * следует добавить отдельный метод applyDiscount(int $percent).
     */
    public function multipliedBy(int $multiplier): self
    {
        if ($multiplier < 0) {
            throw new InvalidArgumentException(
                "Множитель не может быть отрицательным (получено {$multiplier})"
            );
        }

        return new self($this->kopecks * $multiplier);
    }

    /**
     * Форматирование для вывода: «1 234,56 ₽».
     *
     * Используется неразрывный пробел (U+00A0) как разделитель тысяч и перед символом ₽.
     */
    public function format(): string
    {
        return format_price($this);
    }

    public function equals(Money $other): bool
    {
        return $this->kopecks === $other->kopecks;
    }

    public function lessThan(Money $other): bool
    {
        return $this->kopecks < $other->kopecks;
    }

    public function greaterThan(Money $other): bool
    {
        return $this->kopecks > $other->kopecks;
    }
}
