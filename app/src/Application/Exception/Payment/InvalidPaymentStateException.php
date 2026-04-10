<?php

declare(strict_types=1);

namespace App\Application\Exception\Payment;

use App\Application\Exception\AppException;

class InvalidPaymentStateException extends AppException
{
    public function __construct()
    {
        parent::__construct('Платіж не може бути оброблений з поточного статусу!');
    }
}
