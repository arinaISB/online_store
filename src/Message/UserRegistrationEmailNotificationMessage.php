<?php

declare(strict_types=1);

namespace App\Message;

use App\Enum\NotificationType;

readonly class UserRegistrationEmailNotificationMessage
{
    public NotificationType $type;

    public function __construct(
        public string $userEmail,
    ) {
        $this->type = NotificationType::EMAIL;
    }
}
