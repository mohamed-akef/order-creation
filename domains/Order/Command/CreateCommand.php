<?php
namespace Foodics\Order\Command;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Foodics\Order\Repository\OrderRepository;
use Foodics\Order\Service\NewOrder\AddProductToOrder;

class CreateCommand
{

    public function __construct(
        private AddProductToOrder $addProductToOrder,
        private OrderRepository $orderRepository,
        private Product $productModel
    ) {}

    public function handle(array $orderProducts, User $user): Order
    {
        $order = $this->orderRepository->createOrder($user);
        foreach ($orderProducts as $orderProduct) {
            $product = $this->productModel->find($orderProduct['product_id']);
            $this->addProductToOrder->execute($order, $product, $orderProduct['quantity']);
        }

        return $this->orderRepository->placeOrder($order);
    }
}
