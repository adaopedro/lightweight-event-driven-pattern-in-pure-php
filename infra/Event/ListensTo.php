<?php

namespace Infra\Event;

#[\Attribute(\Attribute::TARGET_METHOD)]
class ListensTo
{
    public function __construct(public readonly string $event) {}
}
