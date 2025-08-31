<?php

namespace App\Subscriber;

use App\Event\OrderPlaced;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: OrderPlaced::NAME)]
class LogListener
{
    public function __construct(private string $baseDir) {}

    public function __invoke(OrderPlaced $event): void
    {
        $now = new \DateTime();
        $orderId = $event->getOrderId();

        $contents = sprintf(
            "[%s] Order %s placed! Full payload: %s\n",
            $now->format("d-m-Y H:i:s"),
            $orderId,
            json_encode($event->getPayload(), JSON_PRETTY_PRINT)
        );

        $logFile = $this->baseDir . "/logs/" . $orderId . "_" . $now->format('Y-m-d_H-i-s') . ".log";
        file_put_contents($logFile, $contents);

        echo "[Log Listener] Log for order {$orderId} created at {$logFile}!\n";
    }
}
