<?php

declare(strict_types=1);

namespace App\User\Service;

use App\User\Controller\Request\UserRegistrationRequest;
use App\User\Message\UserRegistrationEmailNotificationMessage;
use App\User\Message\UserRegistrationSmsNotificationMessage;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class NotificationService
{
    public function __construct(private MessageBusInterface $messageBus) {}

    /**
     * @throws ExceptionInterface
     */
    public function sendRegistrationNotification(UserRegistrationRequest $dto): void
    {
        if ($dto->phone) {
            $this->messageBus->dispatch(new UserRegistrationSmsNotificationMessage($dto->phone));
        } else {
            $this->messageBus->dispatch(new UserRegistrationEmailNotificationMessage($dto->email));
        }
    }
}
