<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use InvalidArgumentException;

class Amount implements ValueObject
{
    use ValueObjectTrait;

    /**
     * Максимальна сума у копійках (1 млн грн)
     */
    private const int MAX_AMOUNT = 1_000_000_00;

    /**
     * @param int $amount
     */
    public function __construct(int $amount)
    {
        $this->validateAmount($amount);

        $this->value = $amount;
    }

    /**
     * @param float $amount
     * @return self
     */
    public static function fromFloat(float $amount): self
    {
        return new self((int) round($amount * 100));
    }

    /**
     * @return float
     */
    public function toFloat(): float
    {
        return $this->value / 100;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Перевірка валідності суми
     */
    private function validateAmount(int $amount): void
    {
        if ($amount < 0) {
            throw new InvalidArgumentException('Суми не може бути від\'ємною!');
        }

        if ($amount > self::MAX_AMOUNT) {
            throw new InvalidArgumentException(sprintf(
                'Суми не може перевищувати %d копійок!',
                self::MAX_AMOUNT
            ));
        }
    }
}
