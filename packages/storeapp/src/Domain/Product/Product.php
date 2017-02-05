<?php
namespace StoreApp\Domain\Product;

/**
 * Class Product
 * @package StoreApp\Domain\Product
 */
final class Product
{

    /**
     * @var int
     */
    private $id;

    /*
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $price;

    /**
     * Product constructor.
     * @param string $name
     * @param float $price
     */
    public function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}