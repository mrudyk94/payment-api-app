<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Port\Service\PaymentServiceInterface;
use App\Domain\Entity\Payment;
use App\Domain\ValueObject\Amount;
use App\Domain\ValueObject\Currency;
use App\Infrastructure\Repository\PaymentRepository;

class PaymentService implements PaymentServiceInterface
{
    /**
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(
        private readonly PaymentRepository $paymentRepository,
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function createPayment(float $amount, string $currency): Payment
    {
        // TODO Переглянути створення платежу
        $payment = new Payment(
            Amount::fromFloat($amount),
            new Currency($currency)
        );

        $this->paymentRepository->saveAndFlush($payment);

        return $payment;
    }

    /**
     * {@inheritDoc}
     */
    public function processPayment(int $paymentId): ?Payment
    {
        $payment = $this->paymentRepository->findById($paymentId);
        if (!$payment) {
            throw new \InvalidArgumentException('Даний платіж не знайдено!');
        }

        $payment->pending();
        $this->paymentRepository->saveAndFlush($payment);

        // Імітація зовнішнього провайдера
        // Тут можна додати RabbitMQ/Queue для асинхронності

        return $payment;
    }

    /**
     * {@inheritDoc}
     */
    public function handleWebhook(int $paymentId, string $status): ?Payment
    {
        $payment = $this->paymentRepository->findById($paymentId);
        if (!$payment) {
            throw new \InvalidArgumentException('Даний платіж не знайдено!');
        }

        if ($status === 'success') {
            $payment->completed();
        } elseif ($status === 'fail') {
            $payment->failed();
        }

        $this->paymentRepository->saveAndFlush($payment);

        return $payment;
    }
}
