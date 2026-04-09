<?php

declare(strict_types=1);

namespace App\UI\Controller\DTO;

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
        public string $currency
    )
    {
    }
}
