<?php

use App\OrderService;
use App\Subscriber\EmailNotificationSubscriber;
use App\Subscriber\LogSubscriber;

require_once __DIR__ . "/../vendor/autoload.php";

$dispatcher = new Infra\EventDispatcher;
$dispatcher->addSubscriber(new EmailNotificationSubscriber);
$dispatcher->addSubscriber(new LogSubscriber(dirname(__DIR__)));

$data = json_decode($argv[1], true);

$orderService = new OrderService($dispatcher);
$orderService->placeOrder(order: $data);

// Windows Terminal: php .\cmd\place-order.php "{\"orderId\": \"abc-123-000\", \"client\": {\"email\": \"john@gmail.com\"}}"

/*
OrderSchema
{
    "orderId": "GUID/string",
    "datetime": "string",
    "client": {
        "userId": "integer",
        "name": "string",
        "email": "string"
    },
    "products" : [
        {
            "productId": "integer",
            "description": "string",
            "unitOfMeasure": "string",
            "unitPrice": "decimal",
            "quantity": "decimal",
            "taxPercentage": "decimal",
            "taxType": "string",
            "taxAmount": "decimal",
            "setlementAmount": "decimal",
        }
    ],
    "customer": {
        "customerId": "integer",
        "name": "string",
    }
}
*/
