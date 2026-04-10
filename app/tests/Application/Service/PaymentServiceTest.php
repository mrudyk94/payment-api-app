<?php

declare(strict_types=1);

namespace App\Tests\Application\Service;

use App\Application\Service\PaymentService;
use App\Application\Exception\Payment\PaymentNotFoundException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class PaymentServiceTest extends KernelTestCase
{
    private PaymentService $paymentService;

    public function testSameKeyReturnsSamePayment(): void
    {
        $key = 'idem-key-1';

        $p1 = $this->paymentService->createPayment(100, 'UAH', $key);
        $p2 = $this->paymentService->createPayment(100, 'UAH', $key);

        $this->assertSame($p1->getId(), $p2->getId());
    }

    public function testDifferentKeysCreateDifferentPayments(): void
    {
        $p1 = $this->paymentService->createPayment(100, 'UAH', 'key-1');
        $p2 = $this->paymentService->createPayment(100, 'UAH', 'key-2');

        $this->assertNotSame($p1->getId(), $p2->getId());
    }

    public function testCannotProcessNonPendingPayment(): void
    {
        $payment = $this->paymentService->createPayment(100, 'UAH', 'key-status');

        $payment->completed();

        $this->expectException(\DomainException::class);

        $this->paymentService->processPayment($payment->getId());
    }

    public function testPaymentNotFoundThrowsException(): void
    {
        $this->expectException(PaymentNotFoundException::class);

        $this->paymentService->processPayment(999999);
    }

    public function testProcessPaymentSuccess(): void
    {
        $payment = $this->paymentService->createPayment(150, 'UAH', 'key-process');

        $result = $this->paymentService->processPayment($payment->getId());

        $this->assertTrue($result);
    }

    public function testRejectInvalidAmount(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->paymentService->createPayment(-100, 'UAH', 'bad-amount');
    }
}
