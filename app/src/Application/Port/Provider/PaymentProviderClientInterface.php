<?php

declare(strict_types=1);

namespace App\Application\Port\Provider;

interface PaymentProviderClientInterface
{
    /**
     * Відправка платежу
     * @param int $paymentId
     * @param bool $success
     * @return void
     */
    public function sendPaymentResultWebhook(int $paymentId, bool $success): void;
}
