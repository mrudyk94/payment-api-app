<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Doctrine;

use App\Application\Port\Repository\PaymentRepositoryInterface;
use App\Domain\Entity\Payment;
use Doctrine\Persistence\ManagerRegistry;

class PaymentRepository extends AbstractEntityRepository implements PaymentRepositoryInterface
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    /**
     * {@inheritDoc}
     */
    public function findById(int $id): ?Payment
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * {@inheritDoc}
     */
    public function findByIdempotencyKey(string $key): ?Payment
    {
        return $this->findOneBy(['idempotencyKey' => $key]);
    }
}
