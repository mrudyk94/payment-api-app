<?php

declare(strict_types=1);

namespace App\Application\Message;

class ProcessPaymentMessage
{
    /**
     * @param int $paymentId
     */
    public function __construct(
        public int $paymentId
    )
    {
    }
}
