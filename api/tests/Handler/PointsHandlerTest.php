<?php

namespace App\Tests\Handler;

use App\Entity\Prize;
use App\Entity\User;
use App\Handler\PointsHandler;
use PHPUnit\Framework\TestCase;

class PointsHandlerTest extends TestCase
{
    private PointsHandler $handler;

    protected function setUp(): void
    {
        $this->handler = new PointsHandler();
    }

    public function testIsActiveSuccess()
    {
        $this->assertTrue($this->handler->isActive());
    }

    public function testAcceptSuccess()
    {
        $user = new User();
        $prize = new Prize(Prize::TYPE_POINTS, 17, $user);

        $this->assertSame(Prize::STATUS_PENDING, $prize->getStatus());
        $this->assertSame(false, $prize->isProcessed());
        $this->assertSame(0, $user->getPoints());

        $this->handler->accept($prize, $user);

        $this->assertSame(Prize::STATUS_ACCEPT, $prize->getStatus());
        $this->assertSame(true, $prize->isProcessed());
        $this->assertSame(17, $user->getPoints());
    }

    public function testHandleSuccess()
    {
        $user = new User();

        $expectedPrize = $this->handler->handle($user);

        $this->assertSame(Prize::TYPE_POINTS, $expectedPrize->getType());
        $this->assertSame(Prize::STATUS_PENDING, $expectedPrize->getStatus());
        $this->assertSame(false, $expectedPrize->isProcessed());
        $this->assertGreaterThan(0, $expectedPrize->getAmount());
    }
}
