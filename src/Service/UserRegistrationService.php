<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\UserRegistrationDto;
use App\Entity\User;
use App\Enum\GroupName;
use App\Exception\UserAlreadyExistsException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
     * @throws Exception
     */
    public function register(UserRegistrationDto $dto): string
    {
        if ($this->userRepository->findOneBy(['email' => $dto->getEmail()])) {
            throw UserAlreadyExistsException::forEmail($dto->getEmail());
        }

        if ($this->userRepository->findOneBy(['phone' => $dto->getPhone()])) {
            throw UserAlreadyExistsException::forPhone($dto->getPhone());
        }

        return $this->entityManager->wrapInTransaction(function () use ($dto) {
            $user = new User(
                name: $dto->getName(),
                email: $dto->getEmail(),
                phone: $dto->getPhone(),
                password: '',
                groupName: GroupName::CUSTOMER
            );

            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->jwtManager->create($user);
        });
    }
}
