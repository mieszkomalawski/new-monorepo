<?php
// create_product.php
require_once "bootstrap.php";

$newProductName = $argv[1];

$product = new \StoreApp\Domain\Product\Product('asdfdsf', 10);

$entityManager->persist($product);
$entityManager->flush();

echo "Created Product with ID " . $product->getId() . "\n";

$repo = new \StoreApp\Infrastructure\Product\ProductRepositoryDB($entityManager);

$products = $repo->getProducts();

foreach($products as $product){
    echo $product->getPrice() . "\n";
}