<?php

declare(strict_types=1);

namespace Tests\Unit\Service;

use App\User\Controller\Request\UserRegistrationRequest;
use App\User\Message\UserRegistrationSmsNotificationMessage;
use App\User\Service\NotificationService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\TraceableMessageBus;

final class NotificationServiceTest extends TestCase
{
    /**
     * @throws ExceptionInterface
     */
    public function testSendRegistrationNotificationDispatchesSmsMessage(): void
    {
        $dto = new UserRegistrationRequest(
            'Notification User',
            'test@test.com',
            '+1234567890',
            'password',
            'password',
        );

        $realMessageBus = new MessageBus();
        $traceableBus = new TraceableMessageBus($realMessageBus);

        $notificationService = new NotificationService($traceableBus);
        $notificationService->sendRegistrationNotification($dto);

        $dispatchedMessages = $traceableBus->getDispatchedMessages();
        /** @var UserRegistrationSmsNotificationMessage $sentMessage */
        $sentMessage = $dispatchedMessages[0]['message'];

        self::assertCount(1, $dispatchedMessages, '1 message must be sent');
        self::assertInstanceOf(UserRegistrationSmsNotificationMessage::class, $sentMessage);
        self::assertEquals($dto->phone, $sentMessage->getUserPhone());
    }
}
