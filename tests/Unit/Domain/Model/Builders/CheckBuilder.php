<?php


namespace Tests\Unit\Domain\Model\Builders;


use Carbon\Carbon;
use Domain\Model\Review\Check;
use Domain\Model\Review\ServiceDate;

class CheckBuilder
{
    private ServiceDate $serviceDate;

    private int $amount;

    private int $saleConversion;

    public function __construct()
    {
        $validDate = Carbon::parse('today');
        $this->serviceDate = new ServiceDate($validDate->year, $validDate->month, $validDate->day);
        $this->amount = 20000;
        $this->saleConversion = 10;
    }

    public static function aCheck(): CheckBuilder
    {
        return new self();
    }

    public function withServiceDate(ServiceDate $serviceDate)
    {
        $this->serviceDate = $serviceDate;
        return $this;
    }

    public function build(): Check
    {
        return new Check($this->serviceDate, $this->amount, $this->saleConversion);
    }
}
