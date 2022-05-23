<?php

namespace App\Handler;

use App\Entity\Prize;
use App\Entity\User;
use App\Factory\EntityManagerFactory;

class MoneyHandler extends AbstractHandler
{
    private const AMOUNT_MIN = 1;
    private const AMOUNT_MAX = 25;

    private const BANK_LIMIT = 50;

    private int $moneyUsed = 0;

    public function handle(User $user): Prize
    {
        $amount = rand(self::AMOUNT_MIN, self::AMOUNT_MAX);
        $freeMoney = self::BANK_LIMIT - $this->moneyUsed;

        if ($amount > $freeMoney) {
            $amount = $freeMoney;
        }

        return new Prize(Prize::TYPE_MONEY, $amount, $user);
    }

    public function accept(Prize &$prize, ?User &$user = null): void
    {
        $prize->setStatus(Prize::STATUS_ACCEPT);
    }

    public function isActive(): bool
    {
        $entityManagerFactory = new EntityManagerFactory();
        $em = $entityManagerFactory->getEm();

        $this->moneyUsed = array_sum($em->getRepository(Prize::class)->findNotDeclineMoneyPrizes());

        return $this->moneyUsed < self::BANK_LIMIT;
    }
}
