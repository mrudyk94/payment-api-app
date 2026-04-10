<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Exception\Payment\InvalidPaymentStateException;
use App\Application\Exception\Payment\PaymentNotFoundException;
use App\Application\Message\ProcessPaymentMessage;
use App\Application\Port\Service\PaymentServiceInterface;
use App\Domain\Entity\Payment;
use App\Domain\Enum\PaymentStatus;
use App\Domain\ValueObject\Amount;
use App\Domain\ValueObject\Currency;
use App\Infrastructure\Repository\Doctrine\PaymentRepository;
use Symfony\Component\Messenger\MessageBusInterface;

class PaymentService implements PaymentServiceInterface
{
    /**
     * @param PaymentRepository $paymentRepository
     * @param MessageBusInterface $messageBus
     */
    public function __construct(
        private readonly PaymentRepository $paymentRepository,
        private readonly MessageBusInterface $messageBus
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function createPayment(float $amount, string $currency, string $key): Payment
    {
         // Перевіряємо чи вже існує платіж
        $existing = $this->paymentRepository->findByIdempotencyKey($key);

        // Якщо платіж був раніше створений, просто повертаємо по ньому інформацію
        if ($existing) {
            return $existing;
        }

        // Створюємо новий платіж
        $payment = new Payment(
            Amount::fromFloat($amount),
            new Currency($currency),
            $key
        );

        $this->paymentRepository->saveAndFlush($payment);

        return $payment;
    }

    /**
     * {@inheritDoc}
     */
    public function processPayment(int $paymentId): Payment
    {
        $payment = $this->paymentRepository->findById($paymentId);

        if (!$payment) {
            throw new PaymentNotFoundException($paymentId);
        }

        if ($payment->getStatus() !== PaymentStatus::CREATED) {
            throw new InvalidPaymentStateException();
        }

        // Імітація зовнішнього провайдера, відправка в чергу
        $this->messageBus->dispatch(new ProcessPaymentMessage($payment->getId()));

        return $payment;
    }

    /**
     * {@inheritDoc}
     */
    public function handleWebhook(int $paymentId, string $status): ?Payment
    {
        $payment = $this->paymentRepository->findById($paymentId);
        if (!$payment) {
            throw new PaymentNotFoundException($paymentId);
        }

        // Повторний webhook нічого не робить
        if ($payment->getStatus()->isFinal()) {
            return $payment;
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
