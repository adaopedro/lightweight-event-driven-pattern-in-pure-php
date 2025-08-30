<?php

namespace App;

use App\Event\OrderPlaced;
use Infra\EventDispatcher;

class OrderService
{

    public function __construct(
        private EventDispatcher $dispatcher
    ) {}

    public function placeOrder(array $order): void
    {
        // Perform some action 

        // Then...

        $this->dispatcher->dispatch(
            event: new OrderPlaced(payload: $order)
        );
    }
}
