<?php

namespace App\Handler;

use App\Entity\Prize;
use App\Entity\User;

class PointsHandler extends AbstractHandler
{
    private const AMOUNT_MIN = 1;
    private const AMOUNT_MAX = 100;

    public function handle(User $user): Prize
    {
        $amount = rand(self::AMOUNT_MIN, self::AMOUNT_MAX);

        return new Prize(Prize::TYPE_POINTS, $amount, $user);
    }

    public function accept(Prize &$prize, ?User &$user = null): void
    {
        $prize->setStatus(Prize::STATUS_ACCEPT);
        $prize->setProcessed(true);
        $user->setPoints($user->getPoints() + $prize->getAmount());
    }

    public function isActive(): bool
    {
        return true;
    }
}
