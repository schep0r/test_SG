<?php

namespace App\Service;

use App\Entity\Prize;
use App\Entity\User;
use App\Handler\AbstractHandler;
use App\Handler\GiftHandler;
use App\Handler\MoneyHandler;
use App\Handler\PointsHandler;

class PrizeService
{
    const PRIZE_HANDLERS = [
        Prize::TYPE_POINTS => PointsHandler::class,
        Prize::TYPE_MONEY  => MoneyHandler::class,
        Prize::TYPE_GIFT   => GiftHandler::class,
    ];

    public function generatePrize(User $user): Prize
    {
        $activeHandlers = $this->getActiveHandlers();
        $type = array_rand($activeHandlers);
        $handler = self::PRIZE_HANDLERS[$type];

        return (new $handler)->handle($user);
    }

    public function accept(Prize &$prize, User &$user): void
    {
        $handler = self::PRIZE_HANDLERS[$prize->getType()];

        (new $handler)->accept($prize, $user);
    }

    private function getActiveHandlers(): array
    {
        $result = [];

        foreach (self::PRIZE_HANDLERS as $type => $handlerClass) {
            /** @var AbstractHandler $handler */
            $handler = new $handlerClass;

            if ($handler->isActive()) {
                $result[$type] = $handlerClass;
            }
        }

        return $result;
    }
}
