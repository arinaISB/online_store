<?php

namespace Tests\Unit\Service;

use App\Dto\UserRegistrationDto;
use App\Message\UserRegistrationSmsNotificationMessage;
use App\Service\NotificationService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class NotificationServiceTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function testSendRegistrationNotificationDispatchesSmsMessage(): void
    {
        $dto = new UserRegistrationDto(
            'Notification User',
            'test@test.com',
            '+1234567890',
            'password',
            'password'
        );

        $messageBusMock = $this->createMock(MessageBusInterface::class);

        $messageBusMock
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->callback(function ($message) use ($dto) {
                    return $message instanceof UserRegistrationSmsNotificationMessage &&
                        $message->getUserPhone() === $dto->phone;
                })
            )
            ->willReturn(new Envelope(new \stdClass()));

        $notificationService = new NotificationService($messageBusMock);
        $notificationService->sendRegistrationNotification($dto);
    }
}
