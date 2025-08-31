<?php

namespace App\Subscriber;

use App\Event\OrderPlaced;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: OrderPlaced::NAME)]
class EmailNotificationListener
{

    public function __invoke(OrderPlaced $event)
    {
        $orderId = $event->getOrderId();
        $clientEmail = $event->getClientEmail();

        echo "[Email Notification Listener] Order {$orderId} placed! Sending email to client {$clientEmail}\n";

        // $this->emailService->sendOrderConfirmation($event);
    }
}
