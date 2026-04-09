<?php

declare(strict_types=1);

namespace App\UI\Controller\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class WebhookPaymentInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        public int $id,

        #[Assert\NotBlank]
        #[Assert\Choice(choices: ['success', 'fail'])]
        public string $status
    )
    {
    }
}
