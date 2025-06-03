<?php

declare(strict_types=1);

namespace App\Product\EventListener;

use App\Product\Exception\ProductNotFoundException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION, priority: 1000)]
final class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ProductNotFoundException) {
            $event->setThrowable(new BadRequestHttpException($exception->getMessage(), $exception));
        }
    }
}
