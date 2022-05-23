<?php

namespace App\Service;

use App\Entity\User;
use App\Factory\EntityManagerFactory;
use Doctrine\ORM\EntityManager;

class AuthService
{
    public function getUser(array $data): ?User
    {
        $userRepository = $this->getEm()->getRepository(User::class);
        if (!$user = $userRepository->findOneByLogin($data['login'])) {
            return null;
        }

        if (!hash_equals($user->getPassword(), hash("sha256", $data["password"]))) {
            return null;
        }

        return $user;
    }

    private function getEm(): EntityManager
    {
        return (new EntityManagerFactory())->getEm();
    }
}
