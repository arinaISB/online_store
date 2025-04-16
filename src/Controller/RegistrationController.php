<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UserRegistrationDto;
use App\Service\UserRegistrationService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly UserRegistrationService $registrationService,
    ) {
    }

    #[Route('/api/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        file_put_contents('debug.log', "\n function register");

        try {
            $content = $request->getContent();
            if (empty($content)) {
                throw new InvalidArgumentException('Request body is empty');
            }

            $dto = $this->serializer->deserialize(
                $content,
                UserRegistrationDto::class,
                'json'
            );

            $violations = $this->validator->validate($dto);

            if ($violations->count() > 0) {
                $errors = [];
                foreach ($violations as $violation) {
                    $errors[] = [
                        'field' => $violation->getPropertyPath(),
                        'message' => $violation->getMessage()
                    ];
                }
                throw new InvalidArgumentException(json_encode($errors));
            }

            $token = $this->registrationService->register($dto);

            return $this->json(['token' => $token], Response::HTTP_CREATED);
        } catch (Throwable $th) {
            return new JsonResponse(
                ['message' => 'Registration failed: ' . $th->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
