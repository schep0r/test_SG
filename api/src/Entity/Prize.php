<?php
namespace App\Entity;

use App\Repository\PrizeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrizeRepository::class)]
#[ORM\Table(name: "prizes")]
class Prize
{
    public const STATUS_PENDING = 1;
    public const STATUS_ACCEPT = 2;
    public const STATUS_DECLINE = 3;

    public const TYPE_POINTS = 1;
    public const TYPE_MONEY = 2;
    public const TYPE_GIFT = 3;

    public const TYPE_NAMES = [
        self::TYPE_POINTS => 'Points',
        self::TYPE_MONEY  => 'Money',
        self::TYPE_GIFT   => 'Gift',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected ?int $id;

    #[ORM\Column(type: 'integer')]
    protected int $type;

    #[ORM\Column(type: 'integer')]
    protected int $amount = 0;

    #[ORM\Column(type: 'integer')]
    protected int $status;

    #[ORM\Column(type: 'boolean')]
    protected bool $processed = false;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'prizes')]
    #[ORM\JoinColumn(nullable: false)]
    protected User $user;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    protected ?string $giftType = null;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(int $type, int $amount, User $user)
    {
        $this->type = $type;
        $this->amount = $amount * 100;
        $this->user = $user;
        $this->status = self::STATUS_PENDING;
        $this->createdAt = new \DateTimeImmutable("now");
    }

    public function __clone(): void
    {
        $this->id = null;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount / 100;
    }

    public function isProcessed(): bool
    {
        return $this->processed;
    }

    public function setProcessed(bool $processed): self
    {
        $this->processed = $processed;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getTypeName(): string
    {
        return self::TYPE_NAMES[$this->type] ?? "Undefined type";
    }

    public function getGiftType(): ?string
    {
        return $this->giftType;
    }

    public function setGiftType(?string $giftType): self
    {
        $this->giftType = $giftType;

        return $this;
    }
}
