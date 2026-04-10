<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\Traits\EntityId;
use App\Domain\Entity\Traits\Timestampable;
use App\Domain\Enum\PaymentStatus;
use App\Domain\ValueObject\Amount;
use App\Domain\ValueObject\Currency;
use App\Infrastructure\Repository\Doctrine\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'payment')]
#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment implements EntityInterface
{
    use EntityId;
    use Timestampable;

    #[ORM\Column(name: 'amount', type: 'vo_amount')]
    private Amount $amount;

    #[ORM\Column(name: 'currency', type: 'vo_currency', length: 3)]
    private Currency $currency;

    #[ORM\Column(name: 'status', type: Types::STRING, length: 20, enumType: PaymentStatus::class)]
    private PaymentStatus $status;

    #[ORM\Column(name: 'idempotencyKey', type: 'string', unique: true)]
    private string $idempotencyKey;

    /**
     * @param Amount $amount
     * @param Currency $currency
     * @param $idempotencyKey
     */
    public function __construct(
        Amount $amount,
        Currency $currency,
        $idempotencyKey
    )
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->status = PaymentStatus::CREATED;
        $this->idempotencyKey = $idempotencyKey;
    }

    /**
     * @return Amount
     */
    public function getAmount(): Amount
    {
        return $this->amount;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @return PaymentStatus
     */
    public function getStatus(): PaymentStatus
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getIdempotencyKey(): string
    {
        return $this->idempotencyKey;
    }

    /**
     * @return void
     */
    public function pending(): void
    {
        if ($this->status !== PaymentStatus::CREATED) {
            throw new \DomainException('Платіж можна обробити лише  статусі CREATED!');
        }

        $this->status = PaymentStatus::PENDING;
    }

    /**
     * @return void
     */
    public function completed(): void
    {
        // Повторні webhook не ламають систему
        if (in_array($this->status, [PaymentStatus::COMPLETED, PaymentStatus::FAILED])) {
            return;
        }

        $this->status = PaymentStatus::COMPLETED;
    }

    /**
     * @return void
     */
    public function failed(): void
    {
        // Повторні webhook не ламають систему
        if (in_array($this->status, [PaymentStatus::COMPLETED, PaymentStatus::FAILED])) {
            return;
        }

        $this->status = PaymentStatus::FAILED;
    }
}
