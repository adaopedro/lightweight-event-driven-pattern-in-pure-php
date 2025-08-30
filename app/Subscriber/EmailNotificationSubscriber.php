<?php

namespace App\Subscriber;

use App\Event\OrderPlaced;
use Infra\Event\ListensTo;

class EmailNotificationSubscriber
{

    #[ListensTo(event: OrderPlaced::class )]
    public function onOrderPlaced(OrderPlaced $event): void
    {
        echo "[Email Notification Subscriber] Order {$event->payload['orderId']} placed! Sending email to client {$event->payload['client']['email']} \n";
    }
}
