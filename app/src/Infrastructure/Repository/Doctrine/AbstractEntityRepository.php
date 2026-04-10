<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Doctrine;

use App\Application\Exception\AppException;
use App\Application\Port\Repository\EntityRepositoryInterface;
use App\Domain\Entity\EntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Throwable;

abstract class AbstractEntityRepository extends ServiceEntityRepository implements EntityRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function saveAndFlush(EntityInterface $entity): mixed
    {
        $em = $this->getEntityManager();

        try {
            $em->persist($entity);
            $em->flush();
        } catch (Throwable $exception) {
            throw new AppException($exception->getMessage(), 0, $exception);
        }

        return $entity;
    }

    /**
     * @inheritDoc
     */
    public function deleteAndFlash(EntityInterface $entity): void
    {
        $em = $this->getEntityManager();
        try {
            $em->remove($entity);
            $em->flush();
        } catch (Throwable $exception) {
            throw new AppException($exception->getMessage(), 0, $exception);
        }
    }
}
