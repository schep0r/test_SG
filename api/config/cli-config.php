<?php

require_once __DIR__.'/../vendor/autoload.php';

$entityManager = (new \App\Factory\EntityManagerFactory())->getEm();

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
