<?php

namespace App\Application\Port\Service;

use App\Domain\Entity\Payment;

interface PaymentServiceInterface
{
    /**
     * Створення платежу
     * @param float $amount
     * @param string $currency
     * @return Payment
     */
    public function createPayment(float $amount, string $currency): Payment;

    /**
     * @param int $paymentId
     * @return Payment|null
     */
    public function processPayment(int $paymentId): ?Payment;

    /**
     * @param int $paymentId
     * @param string $status
     * @return Payment|null
     */
    public function handleWebhook(int $paymentId, string $status): ?Payment;
}
