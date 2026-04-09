<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use InvalidArgumentException;

final class Currency implements ValueObject
{
    use ValueObjectTrait;

    private const array SUPPORTED = ['UAH'];

    /**
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->validateCurrency($code);

        $this->value = $code;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Перевірка валідності валюти
     */
    private function validateCurrency(string $code): void
    {
        $normalized = strtoupper(trim($code));

        if (!in_array($normalized, self::SUPPORTED, true)) {
            throw new InvalidArgumentException(sprintf(
                'Підтримується лише валюта: %s. Введено: %s',
                implode(', ', self::SUPPORTED),
                $code
            ));
        }
    }
}
