<?php

namespace Tests\Unit\Service;

use App\Dto\UserRegistrationDto;
use App\Entity\User;
use App\Enum\GroupName;
use App\Exception\UserAlreadyExistsException;
use App\Repository\UserRepository;
use App\Service\UserRegistrationService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationServiceTest extends TestCase
{
    private UserRegistrationService $service;
    private UserRepository $userRepositoryMock;
    private UserPasswordHasherInterface $passwordHasherMock;
    private EntityManagerInterface $entityManagerMock;
    private JWTTokenManagerInterface $jwtManagerMock;
    private UserRegistrationDto $validDto;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $this->passwordHasherMock = $this->createMock(UserPasswordHasherInterface::class);
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->jwtManagerMock = $this->createMock(JWTTokenManagerInterface::class);

        $this->service = new UserRegistrationService(
            $this->userRepositoryMock,
            $this->passwordHasherMock,
            $this->entityManagerMock,
            $this->jwtManagerMock
        );

        $this->validDto = new UserRegistrationDto(
            'Arina',
            'arina@gmail.com',
            '+1234567890',
            'secure123',
            'secure123'
        );
    }

    public function testSuccessfulRegistration(): void
    {
        $token = 'jwt_token';
        $hashedPassword = 'hashed_password';

        $this->userRepositoryMock
            ->method('findOneBy')
            ->willReturn(null);

        $this->passwordHasherMock
            ->expects($this->once())
            ->method('hashPassword')
            ->with(
                $this->isInstanceOf(User::class),
                'secure123'
            )
            ->willReturn($hashedPassword);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('persist')
            ->with($this->callback(function (User $user) use ($hashedPassword) {
                return $user->getName() === $this->validDto->name
                    && $user->getEmail() === $this->validDto->email
                    && $user->getPhone() === $this->validDto->phone
                    && $user->getPassword() === $hashedPassword
                    && $user->getGroupName() === GroupName::CUSTOMER;
            }));

        $this->entityManagerMock
            ->expects($this->once())
            ->method('flush');

        $this->jwtManagerMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($token);

        $result = $this->service->register($this->validDto);

        $this->assertEquals($token, $result);
    }

    public function testRegistrationFailsWithExistingEmail(): void
    {
        $existingUser = new User(
            $this->validDto->name,
            $this->validDto->email,
            $this->validDto->phone,
            $this->validDto->password,
            GroupName::CUSTOMER
        );

        $this->userRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => $this->validDto->email])
            ->willReturn($existingUser);

        $this->expectException(UserAlreadyExistsException::class);
        $this->expectExceptionMessage(sprintf('User with email "%s" already exists', $this->validDto->email));

        $this->service->register($this->validDto);
    }

    public function testRegistrationFailsWithExistingPhone(): void
    {
        $existingUser = new User(
            $this->validDto->name,
            $this->validDto->email,
            $this->validDto->phone,
            $this->validDto->password,
            GroupName::CUSTOMER
        );

        $this->userRepositoryMock
            ->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturnCallback(function (array $criteria) use ($existingUser) {
                if (isset($criteria['email'])) {
                    return null;
                }

                if (isset($criteria['phone'])) {
                    return $existingUser;
                }

                $this->fail('Unexpected criteria in findOneBy');
            });

        $this->expectException(UserAlreadyExistsException::class);
        $this->expectExceptionMessage(sprintf('User with phone "%s" already exists', $this->validDto->phone));

        $this->service->register($this->validDto);
    }
}
