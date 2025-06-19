<?php

declare(strict_types=1);

namespace App\Report\Service;

use App\Order\Entity\OrderItem;
use App\Order\Repository\OrderItemRepository;
use App\Report\Entity\Report;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

final readonly class SalesReportService
{
    public function __construct(
        private OrderItemRepository $orderItemRepository,
        private EntityManagerInterface $entityManager,
        private string $reportsDir,
    ) {}

    public function generate(Uuid $uuid): void
    {
        $filePath = \sprintf('%s/report_%s.jsonl', $this->reportsDir, $uuid->toString());
        $report = new Report($uuid, 'in_progress', $filePath);
        $this->entityManager->persist($report);
        $this->entityManager->flush();

        try {
            $yesterday = new \DateTimeImmutable('yesterday');

            $items = $this->orderItemRepository->findAllSoldItems(
                $yesterday->setTime(0, 0),
                $yesterday->setTime(23, 59, 59),
            );

            $handle = fopen($filePath, 'w');

            /** @var OrderItem $item */
            foreach ($items as $item) {
                fwrite($handle, json_encode([
                    'product_name' => $item->getProduct()->getName(),
                    'price' => $item->getPrice(),
                    'amount' => $item->getQuantity(),
                    'user' => ['id' => $item->getOrder()->getUser()->getId()],
                ], JSON_UNESCAPED_UNICODE) . "\n");
            }
            fclose($handle);

            $report->setStatus('success');
        } catch (\Throwable $exception) {
            $report->setStatus('fail');
            file_put_contents('debug.log', print_r($exception->getMessage(), true), FILE_APPEND);
        }

        $this->entityManager->flush();
    }
}
