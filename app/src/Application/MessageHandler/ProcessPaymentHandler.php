<?php

declare(strict_types=1);

namespace App\Application\MessageHandler;

use App\Application\Message\ProcessPaymentMessage;
use App\Application\Port\Provider\PaymentProviderClientInterface;
use App\Domain\Enum\PaymentStatus;
use App\Infrastructure\Repository\Doctrine\PaymentRepository;
use Random\RandomException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ProcessPaymentHandler
{
    /**
     * @param PaymentRepository $paymentRepository
     * @param PaymentProviderClientInterface $paymentProviderClient
     */
    public function __construct(
        private readonly PaymentRepository $paymentRepository,
        private readonly PaymentProviderClientInterface $paymentProviderClient
    )
    {
    }

    /**
     * @throws RandomException
     */
    public function __invoke(ProcessPaymentMessage $message): void
    {
        // Імітація виклику зовнішнього провайдера
        $payment = $this->paymentRepository->findById($message->paymentId);

        if (!$payment) {
            return;
        }

        // Вже оброблено або змінився статус
        if ($payment->getStatus() !== PaymentStatus::CREATED) {
            return;
        }

        // Змінюємо статус платежу
        $payment->pending();
        $this->paymentRepository->saveAndFlush($payment);

        // Наприклад, 70% успіху
        $random = random_int(1, 100);
        if ($random <= 70) {
            $this->paymentProviderClient->sendPaymentResultWebhook($payment->getId(), true);
        } else {
            $this->paymentProviderClient->sendPaymentResultWebhook($payment->getId(), false);
        }
    }
}
