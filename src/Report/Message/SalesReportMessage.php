<?php

declare(strict_types=1);

namespace App\Report\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Uid\Uuid;

#[AsMessage]
final readonly class SalesReportMessage
{
    public function __construct(
        public Uuid $uuid,
    ) {}
}
