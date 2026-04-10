<?php

declare(strict_types=1);

namespace App\Application\Exception\Payment;

use App\Application\Exception\AppException;

class PaymentNotFoundException extends AppException
{
    public function __construct(int $paymentId)
    {
        parent::__construct(
            sprintf('Даний платіж під номером %d не знайдено!', $paymentId)
        );
    }
}
