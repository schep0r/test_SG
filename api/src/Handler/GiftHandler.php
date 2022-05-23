<?php

namespace App\Handler;

use App\Entity\Prize;
use App\Entity\User;
use App\Factory\EntityManagerFactory;

class GiftHandler extends AbstractHandler
{
    public const GIFT_TYPES = [
        "Toy",
        "PS5",
        "Cola",
        "Bike",
        "Handshake",
    ];

    public function handle(User $user): Prize
    {
        $ableTypes = $this->getAbleGiftTypes();
        $prize = new Prize(Prize::TYPE_GIFT, 1, $user);
        $prize->setGiftType($ableTypes[array_rand($ableTypes)]);

        return $prize;
    }

    public function accept(Prize &$prize, ?User &$user = null): void
    {
        $prize->setStatus(Prize::STATUS_ACCEPT);
    }

    public function isActive(): bool
    {
        return !empty($this->getAbleGiftTypes());
    }

    private function getAbleGiftTypes(): array
    {
        $entityManagerFactory = new EntityManagerFactory();
        $em = $entityManagerFactory->getEm();

        $usedGiftTypes = $em->getRepository(Prize::class)->findUsedGifts();

        return array_diff(self::GIFT_TYPES, $usedGiftTypes);
    }
}
