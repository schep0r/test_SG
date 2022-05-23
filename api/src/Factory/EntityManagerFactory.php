<?php

namespace App\Factory;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\ORMSetup;

class EntityManagerFactory
{
    public function getEm(): EntityManager
    {
        $paths = [__DIR__ . "/../../src/Entity"];

        $driver = new AttributeDriver($paths);

        $config = ORMSetup::createAttributeMetadataConfiguration($paths, true);
        $config->setMetadataDriverImpl($driver);

        $conn = array(
            'driver'   => 'pdo_mysql',
            'host'     => 'db-sg',
            'dbname'   => 'sg',
            'user'     => 'root',
            'password' => '123456'
        );

        return EntityManager::create($conn, $config);
    }
}
