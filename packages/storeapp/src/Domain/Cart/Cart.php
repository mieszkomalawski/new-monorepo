<?php
namespace StoreApp\Domain\Cart;

use StoreApp\Domain\Product\Product;

/**
 * Class Cart
 * @package StoreApp\Domain\Cart
 */
class Cart
{

    /**
     * @var array
     */
    private $products = [];

    /**
     * @param Product $product
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        if (count($this->products) === 0) {
            return 0.00;
        }

        return array_sum(
            array_map(
                function (Product $product) {
                    return $product->getPrice();
                },
                $this->products
            )
        );
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }
}