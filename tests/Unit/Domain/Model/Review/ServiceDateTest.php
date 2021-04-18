<?php


namespace Tests\Unit\Domain\Model\Review;

use Carbon\Carbon;
use Domain\Model\Assessment\Exceptions\InvalidServiceDateException;
use Domain\Model\Assessment\ServiceDate;
use PHPUnit\Framework\TestCase;

class ServiceDateTest extends TestCase
{
    public function test_has_a_valid_date()
    {
        $now = now();
        $creationDate = new ServiceDate($now->year, $now->month, $now->day);

        $this->assertEquals($creationDate->year(),$now->year);
        $this->assertEquals($creationDate->month(),$now->month);
        $this->assertEquals($creationDate->day(),$now->day);
    }

    public function test_expects_exception_when_creation_date_greater_than_today()
    {
        $now = Carbon::parse('+ 1 day');

        $this->expectException(InvalidServiceDateException::class);

        new ServiceDate($now->year, $now->month, $now->day);
    }
}

