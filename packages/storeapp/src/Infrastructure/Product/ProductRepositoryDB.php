<?php
namespace StoreApp\Infrastructure\Product;

use Doctrine\ORM\EntityManager;
use StoreApp\Domain\Product\Product;
use StoreApp\Domain\Product\ProductRepository;

/**
 * Class ProductRepositoryDB
 * @package StoreApp\Infrastructure\Product
 */
class ProductRepositoryDB implements ProductRepository
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $ormRepo;

    /**
     * ProductRepositoryDB constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->ormRepo = $entityManager->getRepository('StoreApp\Domain\Product\Product');
    }

    /**
     * @param string $name
     * @return Product
     */
    public function getProductByName(string $name): Product
    {
        return $this->ormRepo->findOneBy([
            'name' => $name
        ]);
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->ormRepo->findAll();
    }

}