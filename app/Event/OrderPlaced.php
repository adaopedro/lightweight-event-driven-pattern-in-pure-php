<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class OrderPlaced extends Event
{
    public const string NAME = "order.placed";

    public function __construct(public readonly array $payload) {}

    public function getOrderId(): string
    {
        return $this->payload["orderId"];
    }

    public function getClientEmail(): string
    {
        return $this->payload["client"]["email"] ?? "";
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
