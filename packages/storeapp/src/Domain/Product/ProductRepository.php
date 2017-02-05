<?php
namespace StoreApp\Domain\Product;

/**
 * Interface ProductRepository
 * @package StoreApp\Domain\Product
 */
interface ProductRepository
{
    /**
     * @param string $name
     * @return Product
     */
    public function getProductByName(string $name): Product;

    /**
     * @return Product[]
     */
    public function getProducts(): array;
}