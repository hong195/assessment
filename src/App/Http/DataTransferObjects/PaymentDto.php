<?php


namespace App\Http\DataTransferObjects;


class PaymentDto
{
    public $amount;

    public $user;

    public $accountId;

    public function __construct($amount, $user, $accountId)
    {
        $this->amount = $amount;
        $this->user = $user;
        $this->accountId = $accountId;
    }
}
