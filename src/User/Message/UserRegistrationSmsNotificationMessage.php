<?php

declare(strict_types=1);

namespace App\User\Message;

use App\User\Enum\NotificationType;

final readonly class UserRegistrationSmsNotificationMessage
{
    public NotificationType $type;

    public function __construct(
        public string $userPhone,
    ) {
        $this->type = NotificationType::SMS;
    }

    public function getUserPhone(): string
    {
        return $this->userPhone;
    }
}
