<?php

declare(strict_types=1);

namespace App\Infrastructure\Provider;

use App\Application\Port\Provider\PaymentProviderClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class FakePaymentProviderClient implements PaymentProviderClientInterface
{
    /**
     * @param HttpClientInterface $httpClient
     * @param string $webhookUrl
     */
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string              $webhookUrl
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function sendPaymentResultWebhook(int $paymentId, bool $success): void
    {
        $this->httpClient->request('POST', $this->webhookUrl, [
            'json' => [
                'id' => $paymentId,
                'status' => $success ? 'success' : 'fail',
            ],
        ]);
    }
}
