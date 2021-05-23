<?php


namespace Tests\Unit\Domain\Model\Builders;


use Carbon\Carbon;
use Domain\Model\Assessment\Check;
use Domain\Model\Assessment\ServiceDate;

class CheckBuilder
{
    private \DateTime $serviceDate;

    private int $amount;

    private int $saleConversion;

    public function __construct()
    {
        $validDate = Carbon::parse('today');
        $this->serviceDate = (new \DateTime())->setDate($validDate->year, $validDate->month, $validDate->day);
        $this->amount = 20000;
        $this->saleConversion = 10;
    }

    public static function aCheck(): CheckBuilder
    {
        return new self();
    }

    public function withServiceDate(\DateTime $serviceDate)
    {
        $this->serviceDate = $serviceDate;
        return $this;
    }

    public function build(): Check
    {
        return new Check($this->serviceDate, $this->amount, $this->saleConversion);
    }
}
