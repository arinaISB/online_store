<?php

declare(strict_types=1);

namespace App\User\Message;

use App\User\Enum\NotificationType;

final readonly class UserRegistrationEmailNotificationMessage
{
    public NotificationType $type;

    public function __construct(
        public string $userEmail,
    ) {
        $this->type = NotificationType::EMAIL;
    }
}
