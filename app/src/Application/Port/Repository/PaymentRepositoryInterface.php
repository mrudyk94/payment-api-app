<?php

declare(strict_types=1);

namespace App\Application\Port\Repository;

use App\Domain\Entity\Payment;

interface PaymentRepositoryInterface extends EntityRepositoryInterface
{
    /**
     * Отримати платіж по ID
     * @param int $id
     * @return Payment|null
     */
    public function findById(int $id): ?Payment;
}
