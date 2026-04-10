<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\CustomType;

use App\Domain\ValueObject\Amount;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Кастомний Doctrine тип для Value Object Amount
 */
final class AmountType extends Type
{
    public const string TYPE_NAME = 'vo_amount';

    /***
     * @inheritDoc
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof Amount) {
            return $value;
        }

        return parent::convertToDatabaseValue(
            $value->getValue(),
            $platform
        );
    }

    /***
     * @inheritDoc
     * @return mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): mixed {
        if ($value === null) {
            return null;
        }

        return new Amount(parent::convertToPHPValue(
            $value,
            $platform
        ));
    }

    /**
     * @param AbstractPlatform $platform
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * @param array $column
     * @param AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'INTEGER';
    }
}
