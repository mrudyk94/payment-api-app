<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\Traits\EntityId;
use App\Domain\Entity\Traits\Timestampable;
use App\Domain\Enum\PaymentStatus;
use App\Domain\ValueObject\Amount;
use App\Domain\ValueObject\Currency;
use App\Infrastructure\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'payment')]
#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment implements EntityInterface
{
    use EntityId;
    use Timestampable;

    #[ORM\Column(name: 'amount', type: 'vo_amount', enumType: null)]
    private Amount $amount;

    #[ORM\Column(name: 'currency', type: 'vo_currency', length: 3, enumType: null)]
    private Currency $currency;

    #[ORM\Column(name: 'status', type: Types::STRING, length: 20, enumType: PaymentStatus::class)]
    private PaymentStatus $status;

    /**
     * @param Amount $amount
     * @param Currency $currency
     */
    public function __construct(
        Amount $amount,
        Currency $currency
    )
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->status = PaymentStatus::CREATED;
    }

    /**
     * @return Amount
     */
    public function getAmount(): Amount
    {
        return $this->amount;
    }

    /**
     * @param Amount $amount
     * @return void
     */
    public function setAmount(Amount $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     * @return void
     */
    public function setCurrency(Currency $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return PaymentStatus
     */
    public function getStatus(): PaymentStatus
    {
        return $this->status;
    }

    /**
     * @param PaymentStatus $status
     * @return void
     */
    public function setStatus(PaymentStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @return void
     */
    public function pending(): void
    {
        if ($this->status != PaymentStatus::CREATED) {
            throw new \LogicException('Платіж можна обробити лише  статусі CREATED!');
        }

        $this->status = PaymentStatus::PENDING;
    }

    /**
     * @return void
     */
    public function completed(): void
    {
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
        if (in_array($this->status, [PaymentStatus::COMPLETED, PaymentStatus::FAILED])) {
            return;
        }

        $this->status = PaymentStatus::FAILED;
    }
}
