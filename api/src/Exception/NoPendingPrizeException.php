<?php

namespace App\Exception;

class NoPendingPrizeException extends \Exception
{
    protected $message = "No prize for decline";
}
