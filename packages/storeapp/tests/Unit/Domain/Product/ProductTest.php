<?php
namespace Tests\Unit\Domain\Product;

use PHPUnit\Framework\TestCase;
use StoreApp\Domain\Product\Product;

/**
 * Class ProductTest
 * @package Tests\Unit\Domain\Product
 */
class ProductTest extends TestCase
{
    /**
     * @test
     */
    public function shouldInstantiate()
    {
        $product = new Product('DDD in practice', 10);
    }
}
