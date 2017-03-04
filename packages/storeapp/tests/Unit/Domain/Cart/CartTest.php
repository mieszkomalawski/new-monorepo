<?php
namespace StoreApp\Tests\Unit\Domain\Cart;

use PHPUnit\Framework\TestCase;
use StoreApp\Domain\Cart\Cart;
use StoreApp\Domain\Product\Product;


/**
 * Class CartTest
 * @package StoreApp\Tests\Unit\Domain\Cart
 */
class CartTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAddProduct()
    {
        $product = new Product('DDD in practice', 10);

        $cart = new Cart();

        $cart->addProduct($product);
    }

    /**
     * @test
     */
    public function shouldReturn0PriceForEmptyCart()
    {
        $cart = new Cart();

        $price = $cart->getTotalPrice();

        $a;$b;

        static::assertEquals(0, $price);
    }

    /**
     * @test
     */
    public function shouldReturnTotalPriceOfAllProducts()
    {
        $product = new Product('DDD in practice', 8.49);
        $product1 = new Product('Clean code', 12.99);

        $cart = new Cart();
        $cart->addProduct($product);
        $cart->addProduct($product1);

        $price = $cart->getTotalPrice();

        static::assertEquals(21.48, $price);
    }

    /**
     * @test
     */
    public function shouldReturnProductsInCart()
    {
        $product = new Product('DDD in practice', 8.49);
        $product1 = new Product('Clean code', 12.99);

        $cart = new Cart();
        $cart->addProduct($product);
        $cart->addProduct($product1);

        $products = $cart->getProducts();

        static::assertEquals([$product, $product1], $products);
    }
}
