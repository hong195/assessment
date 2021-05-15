<?php


namespace Tests\Unit\Domain\Model\EfficiencyAnalysis;


use Carbon\Carbon;
use Domain\Model\EfficiencyAnalysis\Month;
use Domain\Model\EfficiencyAnalysis\Exceptions\InvalidRatingMonthException;
use PHPUnit\Framework\TestCase;

class MonthTest extends TestCase
{
    public function test_cannot_create_rating_with_future_date()
    {
        $now = Carbon::parse('+ 1month');

        $this->expectException(InvalidRatingMonthException::class);

        new Month($now->year, $now->month);
    }

    public function test_year_must_be_between_1_and_12()
    {
        $now = Carbon::parse('+ 1month');

        $this->expectException(InvalidRatingMonthException::class);

        new Month($now->year, 13);
    }

    public function test_expects_exception_when_year_is_less_than_2019()
    {
        $this->expectException(InvalidRatingMonthException::class);

        new Month(2019, 1);
    }
}
