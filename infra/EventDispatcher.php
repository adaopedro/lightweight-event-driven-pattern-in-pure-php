<?php

namespace Infra;

use Infra\Event\ListensTo;
use ReflectionAttribute;
use ReflectionClass;

class EventDispatcher
{
    private array $subscribers = [];

    public function addSubscriber(object $subscriber): void
    {
        $reflection = new ReflectionClass($subscriber);

        $methods = $reflection->getMethods();

        /** @var \ReflectionMethod $method */
        foreach ($methods as $method) {
            $attributes = $method->getAttributes(ListensTo::class);

            if (count($attributes) < 1) {
                continue;
            }

            array_map(
                callback: fn(ReflectionAttribute $attribute) => $this->push(event: $attribute->newInstance()->event, subscriber: $subscriber, methodName: $method->getName()),
                array: $attributes
            );
        }
    }

    public function dispatch(object $event): void
    {
        $eventClassName = get_class($event);

        /** @var array[] $subscribers */
        $subscribers = $this->subscribers[$eventClassName];

        foreach ($subscribers as [$obj, $methodName]) {
            try {
                $obj->$methodName($event);
            } catch (\Exception $e) {
                echo "Something went wrong when trying to perform event {$eventClassName}\n";
                // Retry policy
            }
        }
    }

    private function push(string $event, object $subscriber, string $methodName): void
    {
        $this->subscribers[$event][] = [$subscriber, $methodName];
    }
}
