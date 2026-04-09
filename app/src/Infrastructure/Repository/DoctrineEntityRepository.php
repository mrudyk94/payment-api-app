<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Exception\AppException;
use App\Application\Port\Repository\EntityRepositoryInterface;
use App\Domain\Entity\EntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Throwable;

abstract class DoctrineEntityRepository extends ServiceEntityRepository implements EntityRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function saveAndFlush(EntityInterface $entity): mixed
    {
        $em = $this->getEntityManager();

        try {

    //            if (method_exists($entity, 'getAmount')) {
    //                $amount = $entity->getAmount();
    //                $currency = $entity->getCurrency();
    //
    //                dump([
    //                    'amount_type' => gettype($amount),
    //                    'amount_class' => is_object($amount) ? get_class($amount) : 'not_object',
    //                    'currency_type' => gettype($currency),
    //                    'currency_class' => is_object($currency) ? get_class($currency) : 'not_object',
    //                ]);
    //            }
    //
    //            die;

            $em->persist($entity);
            $em->flush();
        } catch (Throwable $exception) {
            throw new AppException($exception->getMessage(), 0, $exception);
        }

//        catch (Throwable $exception) {
//            // Додайте повний стек трейс
//            throw new AppException(
//                $exception->getMessage() . "\n" . $exception->getTraceAsString(),
//                0,
//                $exception
//            );
//        }

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
