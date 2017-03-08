<?php
/**
 * Created by PhpStorm.
 * User: mmalawski
 * Date: 05/02/2017
 * Time: 18:30
 */
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . "/../vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
// or if you prefer yaml or XML
$config = Setup::createYAMLMetadataConfiguration([__DIR__ . "/../config"], $isDevMode);

// database configuration parameters
$conn = [
    'dbname' => 'local',
    'user' => 'dev',
    'password' => 'dev',
    // patrz config dockera
    'host' => 'db',
    'driver' => 'pdo_mysql',
];

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);

//routing
$locator = new FileLocator([__DIR__ . '/../config/routing']);

$requestContext = new \Symfony\Component\Routing\RequestContext();
$requestContext->fromRequest(Request::createFromGlobals());

$router = new \Symfony\Component\Routing\Router(
    new YamlFileLoader($locator),
    'routes.yml',
    ['cache_dir' => __DIR__.'/../cache'],
    $requestContext
);
var_dump($requestContext->getPathInfo());die();
$match = $router->match($requestContext->getPathInfo());
