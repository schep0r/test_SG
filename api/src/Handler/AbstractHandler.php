<?php

namespace App\Handler;

use App\Entity\Prize;
use App\Entity\User;

abstract class AbstractHandler
{
    abstract public function handle(User $user): Prize;

    abstract function isActive(): bool;

    abstract public function accept(Prize &$prize, ?User &$user = null): void;
}
