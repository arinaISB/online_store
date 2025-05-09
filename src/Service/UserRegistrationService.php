<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\UserRegistrationDto;
use App\Entity\User;
use App\Enum\GroupName;
use App\Exception\UserAlreadyExistsException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager,
        private readonly JWTTokenManagerInterface $jwtManager,
    ) {
    }

    /**
     * @param UserRegistrationDto $dto
     * @return string
     * @throws UserAlreadyExistsException
     */
    public function register(UserRegistrationDto $dto): string
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
            groupName: GroupName::CUSTOMER
        );

        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->jwtManager->create($user);
    }
}
