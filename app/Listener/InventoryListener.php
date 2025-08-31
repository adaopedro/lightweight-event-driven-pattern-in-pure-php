<?php

namespace App\Listener;

use App\Event\OrderPlaced;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: OrderPlaced::NAME, priority: 20)]
class InventoryListener
{
    public function __invoke(OrderPlaced $event): void
    {
        $orderId = $event->getOrderId();
        $products = $event->getPayload()['products'] ?? [];

        echo "[Inventory Listener] Processing inventory update for order {$orderId}\n";

        foreach ($products as $product) {
            $productId = $product['productId'] ?? 'unknown';
            $quantity = $product['quantity'] ?? 0;

            echo "  - Updating stock for product {$productId}: -{$quantity} units\n";

            // $this->inventoryService->updateStock($productId, -$quantity);
        }
    }
}
