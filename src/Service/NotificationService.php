<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\UserRegistrationDto;
use App\Message\UserRegistrationEmailNotificationMessage;
use App\Message\UserRegistrationSmsNotificationMessage;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class NotificationService
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function sendRegistrationNotification(UserRegistrationDto $dto): void
    {
        if ($dto->phone) {
            $this->messageBus->dispatch(new UserRegistrationSmsNotificationMessage($dto->phone));
        } else {
            $this->messageBus->dispatch(new UserRegistrationEmailNotificationMessage($dto->email));
        }
    }
}
