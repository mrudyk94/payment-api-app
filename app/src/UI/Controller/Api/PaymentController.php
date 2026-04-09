<?php

declare(strict_types=1);

namespace App\UI\Controller\Api;

use App\Application\Service\PaymentService;
use App\UI\Controller\DTO\CreatePaymentInput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payments', name: 'api_payments_')]
final readonly class PaymentController
{
    /**
     * @param PaymentService $paymentService
     */
    public function __construct(
        private PaymentService $paymentService,
    )
    {
    }

    /**
     * Додаємо платіж
     * @param CreatePaymentInput $input
     * @return JsonResponse
     */
    #[Route('',
        name: 'create',
        methods: ['POST']
    )]
    public function create(
        #[MapRequestPayload] CreatePaymentInput $input
    ): JsonResponse
    {
        $payment = $this->paymentService->createPayment(
            $input->amount,
            $input->currency
        );

        return new JsonResponse([
            'id' => $payment->getId(),
            'amount' => $payment->getAmount(),
            'currency' => $payment->getCurrency(),
            'status' => $payment->getStatus(),
        ], Response::HTTP_CREATED);
    }

    /**
     * Імітує виклик зовнішнього провайдера
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}/process',
        name: 'process',
        methods: ['POST']
    )]
    public function process(
        int $id
    ): JsonResponse
    {
        $payment = $this->paymentService->processPayment($id);

        return new JsonResponse([
            'id' => $payment->getId(),
            'status' => $payment->getStatus(),
        ]);
    }
}
