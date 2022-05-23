<?php

namespace App\Tests\Factory;

use App\Factory\EntityManagerFactory;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class EntityManagerFactoryTest extends TestCase
{

    public function testGetEm()
    {
        $factory = new EntityManagerFactory();

        $result = $factory->getEm();

        $this->assertInstanceOf(EntityManager::class, $result);
    }
}
