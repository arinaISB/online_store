<?php

declare(strict_types=1);

namespace App\Report\Controller;

use App\Report\Message\SalesReportMessage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;

#[AsController]
final readonly class ReportController
{
    public function __construct(
        private MessageBusInterface $bus,
    ) {}

    #[Route('reports', methods: [Request::METHOD_POST])]
    #[IsGranted('ROLE_ADMIN')]
    public function generate(): JsonResponse
    {
        $uuid = Uuid::v4();
        $this->bus->dispatch(new SalesReportMessage($uuid));

        return new JsonResponse(['reportId' => $uuid], Response::HTTP_ACCEPTED);
    }
}
