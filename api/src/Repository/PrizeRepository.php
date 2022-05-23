<?php
namespace App\Repository;

use App\Entity\Prize;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;

class PrizeRepository extends EntityRepository
{
    public function findLastPendingPrize(User $user): ?Prize
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->andWhere('p.user = :user')
            ->setParameter('status', Prize::STATUS_PENDING)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findNotDeclineMoneyPrizes(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.amount / 100')
            ->andWhere('p.type = :typeMoney')
            ->andWhere('p.status <> :statusDecline')
            ->setParameter('typeMoney', Prize::TYPE_MONEY)
            ->setParameter('statusDecline', Prize::STATUS_DECLINE)
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function findUsedGifts(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.giftType')
            ->andWhere('p.type = :typeGift')
            ->andWhere('p.status <> :statusDecline')
            ->setParameter('typeGift', Prize::TYPE_GIFT)
            ->setParameter('statusDecline', Prize::STATUS_DECLINE)
            ->getQuery()
            ->getSingleColumnResult();
    }
    
    public function findMoneyToSend(int $limit): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.type = :typeMoney')
            ->andWhere('p.status = :statusAccept')
            ->andWhere('p.processed = :processed')
            ->orderBy("p.createdAt", "ASC")
            ->setMaxResults($limit)
            ->setParameter('typeMoney', Prize::TYPE_MONEY)
            ->setParameter('statusAccept', Prize::STATUS_ACCEPT)
            ->setParameter('processed', false)
            ->getQuery()
            ->getResult();
    }
}
