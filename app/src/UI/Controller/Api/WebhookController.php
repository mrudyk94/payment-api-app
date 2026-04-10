<?php

declare(strict_types=1);

namespace App\UI\Controller\Api;

use App\Application\Service\PaymentService;
use App\UI\DTO\WebhookPaymentInput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/webhook')]
final readonly class WebhookController
{
    public function __construct(
        private readonly PaymentService $paymentService
    )
    {
    }

    /**
     * Приймаємо HTTP-запити від зовнішнього сервісу (провайдера)
     * @param WebhookPaymentInput $input
     * @return JsonResponse
     */
    #[Route('/payment',
        name: 'webhook_payment',
        methods: ['POST']
    )]
    public function payment(
        #[MapRequestPayload] WebhookPaymentInput $input
    ): JsonResponse
    {
        $payment = $this->paymentService->handleWebhook($input->id, $input->status);
        return new JsonResponse([
            'id' => $payment->getId(),
            'status' => $payment->getStatus(),
        ]);
    }
}
