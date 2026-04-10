<?php

namespace App\Application\Port\Service;

use App\Domain\Entity\Payment;

interface PaymentServiceInterface
{
    /**
     * Створення платежу
     * @param float $amount
     * @param string $currency
     * @param string $key
     * @return Payment
     */
    public function createPayment(float $amount, string $currency, string $key): Payment;

    /**
     * Імітація зовнішнього провайдера, відправка в чергу
     * @param int $paymentId
     * @return Payment
     */
    public function processPayment(int $paymentId): Payment;

    /**
     * Обробка вхідного webhook-запиту від стороннього сервісу.
     * @param int $paymentId
     * @param string $status
     * @return Payment|null
     */
    public function handleWebhook(int $paymentId, string $status): ?Payment;
}
