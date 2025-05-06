<?php

declare(strict_types=1);

namespace Integration\Service;

use App\Dto\UserRegistrationDto;
use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Service\UserRegistrationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRegistrationServiceTest extends WebTestCase
{
    private UserRegistrationService $service;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->service = self::getContainer()->get(UserRegistrationService::class);
    }

    public function testRegister(): void
    {
        $dto = new UserRegistrationDto(
            'Arina',
            'arina@gmail.com',
            '+1234567890',
            'secure123',
            'secure123'
        );

        $jwt = $this->service->register($dto);

        $this->assertNotEmpty($jwt);
        $this->assertNotNull(
            self::getContainer()->get(EntityManagerInterface::class)
                ->getRepository(User::class)
                ->findOneBy(['email' => $dto->email])
        );
    }

    public function testRegisterWithExistingEmail(): void
    {
        $dto = new UserRegistrationDto(
            'John',
            'existingEmail@mail.ru',
            '+1234567890',
            'secure123',
            'secure123'
        );

        $this->service->register($dto);

        $this->expectException(UserAlreadyExistsException::class);
        $this->service->register($dto);
    }
}
