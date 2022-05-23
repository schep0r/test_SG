#!/usr/bin/env php
<?php

require_once __DIR__.'/../vendor/autoload.php';

use App\Entity\User;
use App\Factory\EntityManagerFactory;

$users = [
    ["login" => "user", "password" => "demo1234"],
    ["login" => "test", "password" => "test"],
];

$entityManager = (new EntityManagerFactory())->getEm();

foreach ($users as $user) {
    $entity = new User();
    $entity->setLogin($user["login"]);
    $entity->setPassword( hash("sha256", $user["password"]));

    $entityManager->persist($entity);
}

$entityManager->flush();
