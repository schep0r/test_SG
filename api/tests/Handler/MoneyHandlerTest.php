<?php

namespace App\Tests\Handler;

use App\Entity\Prize;
use App\Entity\User;
use App\Handler\MoneyHandler;
use PHPUnit\Framework\TestCase;

class MoneyHandlerTest extends TestCase
{
    private MoneyHandler $handler;

    protected function setUp(): void
    {
        $this->handler = new MoneyHandler();
    }

    public function testAcceptSuccess()
    {
        $user = new User();
        $prize = new Prize(Prize::TYPE_MONEY, 5, $user);

        $this->handler->accept($prize, $user);

        $this->assertSame(Prize::STATUS_ACCEPT, $prize->getStatus());
    }

    public function testIsActive()
    {
        $this->assertIsBool($this->handler->isActive());
    }

    public function testHandle()
    {
        $user = new User();
        $expectedPrize = $this->handler->handle($user);

        $this->assertSame(Prize::TYPE_MONEY, $expectedPrize->getType());
        $this->assertSame(Prize::STATUS_PENDING, $expectedPrize->getStatus());
        $this->assertSame(false, $expectedPrize->isProcessed());
        $this->assertGreaterThan(0, $expectedPrize->getAmount());
    }
}
