<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum PaymentStatus: string
{
    // Створений, очікує обробки
    case CREATED = 'created';

    // Надісланий провайдеру
    case PENDING = 'pending';

    // Успішно завершено
    case COMPLETED = 'completed';

    // Помилка
    case FAILED = 'failed';

    public function isFinal(): bool
    {
        return in_array($this, [
            self::COMPLETED,
            self::FAILED,
        ], true);
    }
}
