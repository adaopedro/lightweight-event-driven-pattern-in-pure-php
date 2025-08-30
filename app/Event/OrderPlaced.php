<?php

namespace App\Event;

class OrderPlaced
{
    public function __construct(public readonly array $payload) {}
}
