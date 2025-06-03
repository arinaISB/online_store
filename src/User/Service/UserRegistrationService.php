<?php

declare(strict_types=1);

namespace App\User\Service;

use App\User\Controller\Request\UserRegistrationRequest;
use App\User\Entity\User;
use App\User\Enum\GroupName;
use App\User\Exception\UserAlreadyExistsException;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UserRegistrationService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager,
        private JWTTokenManagerInterface $jwtManager,
        private NotificationService $notificationService,
    ) {}

    /**
     * @throws UserAlreadyExistsException
     * @throws ExceptionInterface
     */
    public function register(UserRegistrationRequest $dto): string
    {
        if ($this->userRepository->findOneBy(['email' => $dto->email])) {
            throw UserAlreadyExistsException::forEmail($dto->email);
        }

        if ($this->userRepository->findOneBy(['phone' => $dto->phone])) {
            throw UserAlreadyExistsException::forPhone($dto->phone);
        }

        $user = new User(
            name: $dto->name,
            email: $dto->email,
            phone: $dto->phone,
            password: '',
            groupName: GroupName::CUSTOMER,
        );

        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->notificationService->sendRegistrationNotification($dto);

        return $this->jwtManager->create($user);
    }
}
