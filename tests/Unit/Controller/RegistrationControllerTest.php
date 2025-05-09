<?php

namespace Tests\Unit\Controller;

use App\Controller\RegistrationController;
use App\Dto\UserRegistrationDto;
use App\Exception\UserAlreadyExistsException;
use App\Service\UserRegistrationService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class RegistrationControllerTest extends TestCase
{
    private RegistrationController $controller;
    private UserRegistrationService $registrationServiceMock;
    private UserRegistrationDto $dto;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->registrationServiceMock = $this->createMock(UserRegistrationService::class);
        $this->controller = new RegistrationController($this->registrationServiceMock);

        $this->dto = new UserRegistrationDto(
            'Arina',
            'arina@gmail.com',
            '+1234567890',
            'secure123',
            'secure123'
        );
    }

    /**
     * @throws \Exception
     */
    public function testSuccessfulRegistration(): void
    {
        $token = 'generated_token';

        $this->registrationServiceMock
            ->expects($this->once())
            ->method('register')
            ->with($this->dto)
            ->willReturn($token);

        $response = $this->controller->register($this->dto);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(['token' => $token], json_decode($response->getContent(), true));
    }

    /**
     * @throws \Exception
     */
    public function testServiceExceptionHandling(): void
    {
        $exceptionMessage = sprintf('User with email "%s" already exists', $this->dto->email);

        $this->registrationServiceMock
            ->expects($this->once())
            ->method('register')
            ->with($this->dto)
            ->willThrowException(new UserAlreadyExistsException($exceptionMessage));

        $this->expectException(UserAlreadyExistsException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $this->controller->register($this->dto);
    }
}
