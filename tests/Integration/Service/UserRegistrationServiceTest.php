<?php

declare(strict_types=1);

namespace Integration\Service;

use App\Dto\UserRegistrationDto;
use App\Entity\User;
use App\Service\UserRegistrationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRegistrationServiceTest extends WebTestCase
{
    private UserRegistrationService $service;
    private UserRegistrationDto $dto;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->service = self::getContainer()->get(UserRegistrationService::class);

        $this->dto = new UserRegistrationDto(
            'Arina',
            'arina@gmail.com',
            '+1234567890',
            'secure123',
            'secure123'
        );
    }

    public function testRegister(): void
    {
        $jwt = $this->service->register($this->dto);

        $this->assertNotEmpty($jwt);
        $this->assertNotNull(
            self::getContainer()->get(EntityManagerInterface::class)
                ->getRepository(User::class)
                ->findOneBy(['email' => $this->dto->email])
        );
    }
}
