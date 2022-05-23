<?php

namespace App\Controller;


use App\Entity\Prize;
use App\Entity\User;
use App\Factory\EntityManagerFactory;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AbstractController
{
    private ?EntityManager $em = null;

    public function getEm(): EntityManager
    {
        if (!$this->em) {
            $this->em = (new EntityManagerFactory())->getEm();
        }
        return $this->em;
    }

    protected function getUser(Request $request): User
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonException("Bad JSON data.");
        }

        if (!isset($data['id'])) {
            throw new JsonException("Wrong JSON format.");
        }

        if (!$user = $this->getEm()->getRepository(User::class)->find($data['id'])) {
            throw new NotFoundHttpException("User not found");
        }

        return $user;
    }

    protected function getPrizeResponseArray(Prize $prize): array
    {
        return["prize" => [
            "type" => $prize->getTypeName(),
            "amount" => $prize->getAmount(),
            "gift_type" => $prize->getGiftType(),
            "created_at" => $prize->getCreatedAt()->format("d.m.Y H:i:s")
        ]];
    }
}
