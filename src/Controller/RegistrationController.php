<?php

namespace App\Controller;

use App\Dto\UserRegistrationDto;
use App\Service\UserRegistrationService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

readonly class RegistrationController
{
    public function __construct(
        private UserRegistrationService $registrationService,
    ) {
    }

    /**
     * @param UserRegistrationDto $dto
     * @return JsonResponse
     * @throws Exception
     */
    public function register(#[MapRequestPayload] UserRegistrationDto $dto): JsonResponse
    {
        return new JsonResponse(
            ['token' => $this->registrationService->register($dto)],
            Response::HTTP_CREATED
        );
    }
}
