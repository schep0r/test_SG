<?php

namespace App\Tests\Service;

use App\Entity\Prize;
use App\Entity\User;
use App\Service\PrizeService;
use PHPUnit\Framework\TestCase;

class PrizeServiceTest extends TestCase
{
    private PrizeService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new PrizeService();
    }

    public function testGeneratePrizeSuccess()
    {
        $user = $this->createMock(User::class);
        $user
            ->method("getId")
            ->willReturn(7)
        ;

        $result = $this->service->generatePrize($user);

        $this->assertInstanceOf(Prize::class, $result);
        $this->assertSame(Prize::STATUS_PENDING, $result->getStatus());
        $this->assertSame(7, $result->getUser()->getId());
        $this->assertGreaterThan(1.00, $result->getAmount());

    }

    /**
     * @dataProvider acceptSuccessTests
     */
    public function testAcceptSuccess(int $type, int $amount)
    {
        $user = new User();
        $prize = new Prize($type, $amount, $user);

        $this->service->accept($prize, $user);

        $this->assertSame(Prize::STATUS_ACCEPT, $prize->getStatus());
        if ($type === Prize::TYPE_POINTS) {
            $this->assertSame($amount, $user->getPoints());
        }
    }

    public function acceptSuccessTests(): array
    {
        return [
            [Prize::TYPE_POINTS, 10],
            [Prize::TYPE_MONEY, 3],
            [Prize::TYPE_GIFT, 1],
        ];
    }
}
