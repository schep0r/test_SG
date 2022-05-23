<?php

namespace App\Service;

use App\Entity\Prize;
use App\Exception\WrongPrizeTypeException;

class ConvertService
{
    public const COEFFICIENT_MONEY_TO_POINTS = 2;

    public function convertMoneyToPoints(Prize &$prize): Prize
    {
        if ($prize->getType() !== Prize::TYPE_MONEY) {
            throw new WrongPrizeTypeException("Only money prize can be converted");
        }

        $newPrize = new Prize(
            Prize::TYPE_POINTS,
            self::COEFFICIENT_MONEY_TO_POINTS * $prize->getAmount(),
            $prize->getUser()
        );
        $newPrize->setStatus(Prize::STATUS_ACCEPT);
        $prize->setStatus(Prize::STATUS_DECLINE);

        return $newPrize;
    }
}
