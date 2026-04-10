<?php

declare(strict_types=1);

namespace App\UI\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreatePaymentInput
{
    public function __construct(
        #[Assert\NotNull(message: 'Сума є обовʼязковим параметром!')]
        #[Assert\Positive(message: 'Сума має бути більшою за нуль!')]
        #[Assert\Type('float', message: 'Сума має бути числом!')]
        public float $amount,

        #[Assert\NotBlank(message: 'Валюта є обовʼязковим параметром!')]
        #[Assert\Currency(message: 'Валюта має бути в правильному форматі (ISO 4217)!')]
        #[Assert\Type('string', message: 'Валюта має бути рядком!')]
        public string $currency,

        #[Assert\NotBlank(message: 'Idempotency key є обовʼязковим параметром!')]
        #[Assert\Type('string', message: 'Idempotency key має бути рядком!')]
        #[Assert\Length(
            min: 8,
            max: 64,
            minMessage: 'Idempotency key занадто короткий (мінімум 8 символів)',
            maxMessage: 'Idempotency key занадто довгий (максимум 64 символи)'
        )]
        #[Assert\Regex(
            pattern: '/^[a-zA-Z0-9\-_]+$/',
            message: 'Idempotency key може містити тільки латиницю, цифри, - та _'
        )]
        public string $key
    )
    {
    }
}
