<?php

namespace App;

use App\Event\OrderPlaced;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OrderService
{

    public function __construct(
        private EventDispatcherInterface $eventDispatcher
    ) {}

    public function placeOrder(array $order): void
    {
        $this->validateOrder($order);

        echo "[OrderService] Processing order {$order['orderId']}...\n";

        $event = new OrderPlaced($order);
        $this->eventDispatcher->dispatch($event, OrderPlaced::NAME);

        echo "[OrderService] Order {$order['orderId']} processed successfully!\n";
    }

    private function validateOrder(array $order): void
    {
        if (empty($order["orderId"])) {
            throw new \InvalidArgumentException("Order ID is required");
        }

        if (empty($order["client"]["email"])) {
            throw new \InvalidArgumentException("Client email is required");
        }
    }
}
