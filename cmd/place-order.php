<?php

use App\Event\OrderPlaced;
use App\OrderService;
use App\Listener\{InventoryListener, EmailNotificationListener, LogListener};

require_once __DIR__ . "/../vendor/autoload.php";

if (!isset($argv[1])) {
    echo "Usage: php place-order.php '<json-order-data>'\n";
    echo "Example: php place-order.php '{\"orderId\": \"abc-123-000\", \"client\": {\"email\": \"john@gmail.com\"}}'\n";
    exit(1);
}

try {
    $data = json_decode($argv[1], true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \InvalidArgumentException('Invalid JSON provided: ' . json_last_error_msg());
    }

    $eventDispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher;
    $eventDispatcher->addListener(OrderPlaced::NAME, new EmailNotificationListener, 0);
    $eventDispatcher->addListener(OrderPlaced::NAME, new LogListener(dirname(__DIR__)), 10);
    $eventDispatcher->addListener(OrderPlaced::NAME, new InventoryListener, 20);

    echo "Starting order processing...\n";
    echo str_repeat('-', 50) . "\n";

    $orderService = new OrderService($eventDispatcher);
    $orderService->placeOrder(order: $data);

    echo str_repeat('-', 50) . "\n";
    echo "Order processing completed!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}


/*
Comandos de exemplo:

# Pedido básico
php cmd/place-order.php '{"orderId": "abc-123-000", "client": {"email": "john@gmail.com"}}'

# Pedido completo
php cmd/place-order.php '{
    "orderId": "ord-456-789",
    "datetime": "2024-12-07 14:30:00",
    "client": {
        "userId": 123,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "products": [
        {
            "productId": 1,
            "description": "Produto A",
            "unitOfMeasure": "UN",
            "unitPrice": 50.00,
            "quantity": 2,
            "taxPercentage": 10.00,
            "taxType": "ICMS",
            "taxAmount": 10.00,
            "settlementAmount": 110.00
        }
    ],
    "customer": {
        "customerId": 456,
        "name": "Empresa XYZ"
    }
}'

# Windows (escape das aspas)
php cmd\place-order.php "{\"orderId\": \"abc-123-000\", \"client\": {\"email\": \"john@gmail.com\"}}"
*/