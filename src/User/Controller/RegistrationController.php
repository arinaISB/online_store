<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\User\Controller\Request\UserRegistrationRequest;
use App\User\Exception\UserAlreadyExistsException;
use App\User\Service\UserRegistrationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class RegistrationController
{
    public function __construct(
        private UserRegistrationService $registrationService,
    ) {}

    /**
     * @throws ExceptionInterface
     */
    #[Route('/register', methods: [Request::METHOD_POST])]
    public function register(#[MapRequestPayload] UserRegistrationRequest $dto): JsonResponse
    {
        try {
            return new JsonResponse(
                ['token' => $this->registrationService->register($dto)],
                Response::HTTP_CREATED,
            );
        } catch (UserAlreadyExistsException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }
    }
}
