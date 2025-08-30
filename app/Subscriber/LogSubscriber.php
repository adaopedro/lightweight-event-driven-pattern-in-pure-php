<?php

namespace App\Subscriber;

use App\Event\OrderPlaced;
use Infra\Event\ListensTo;
use DateTime;

class LogSubscriber
{
    public function __construct(private string $baseDir) {}

    #[ListensTo(event: OrderPlaced::class)]
    public function onOrderPlaced(OrderPlaced $event): void
    {
        $now = new DateTime()->format("d-m-Y h:i:s");

        $contents = <<<TEXT
        [$now] Order {$event->payload['orderId']} placed!\n
        TEXT;

        file_put_contents($this->baseDir . "/logs/" . uniqid() . ".log", $contents);

        echo "[Log Subscriber] Log for order {$event->payload['orderId']} created!\n";
    }
}
