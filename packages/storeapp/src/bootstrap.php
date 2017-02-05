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

require_once __DIR__ . "/../vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
// or if you prefer yaml or XML
$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/../config"), $isDevMode);

// database configuration parameters
$conn = array(
    'dbname' => 'local',
    'user' => 'dev',
    'password' => 'dev',
    // patrz config dockera
    'host' => 'db',
    'driver' => 'pdo_mysql',
);

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);