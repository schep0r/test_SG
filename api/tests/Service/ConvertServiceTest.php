<?php

namespace App\Tests\Service;

use App\Entity\Prize;
use App\Entity\User;
use App\Exception\WrongPrizeTypeException;
use App\Service\ConvertService;
use PHPUnit\Framework\TestCase;

class ConvertServiceTest extends TestCase
{
    private ConvertService $service;

    public function setUp(): void
    {
        $this->service = new ConvertService();
    }

    /**
     * @dataProvider convertMoneyToPointsSuccessTests()
     */
    public function testConvertMoneyToPointsSuccess(int $amount, float $expectedAmount)
    {
        $prize = new Prize(Prize::TYPE_MONEY, $amount, $this->createStub(User::class));
        $newPrize = $this->service->convertMoneyToPoints($prize);

        $this->assertSame($expectedAmount, $newPrize->getAmount());
        $this->assertSame(Prize::STATUS_ACCEPT, $newPrize->getStatus());
        $this->assertSame(Prize::TYPE_POINTS, $newPrize->getType());

        $this->assertSame(Prize::STATUS_DECLINE, $prize->getStatus());
    }

    public function convertMoneyToPointsSuccessTests(): array
    {
        return [
            [1, 2],
            [23, 46],
            [6, 12]
        ];
    }

    /**
     * @dataProvider convertMoneyToPointsExceptionTests()
     */
    public function testConvertMoneyToPointsException(int $type)
    {
        $prize = new Prize($type, 2, $this->createStub(User::class));

        $this->expectException(WrongPrizeTypeException::class);
        $this->expectExceptionMessage("Only money prize can be converted");

        $this->service->convertMoneyToPoints($prize);
    }

    public function convertMoneyToPointsExceptionTests(): array
    {
        return [
            [Prize::TYPE_POINTS],
            [Prize::TYPE_GIFT],
        ];
    }
}
