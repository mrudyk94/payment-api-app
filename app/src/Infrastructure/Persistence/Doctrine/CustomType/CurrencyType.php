<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\CustomType;

use App\Domain\ValueObject\Currency;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * Кастомний Doctrine тип для Value Object Currency
 */
final class CurrencyType extends StringType
{
    public const string TYPE_NAME = 'vo_currency';

    /***
     * @inheritDoc
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed {
        if (! $value instanceof Currency) {
            return $value;
        }

        return parent::convertToDatabaseValue(
            $value->asString(),
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

        return new Currency(parent::convertToPHPValue(
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
}
