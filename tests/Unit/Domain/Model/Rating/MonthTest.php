<?php


namespace Tests\Unit\Domain\Model\Rating;


use Carbon\Carbon;
use Domain\Model\Rating\Month;
use Domain\Model\Rating\Exceptions\InvalidRatingMonthException;
use PHPUnit\Framework\TestCase;

class MonthTest extends TestCase
{
    public function test_cannot_create_rating_with_future_date()
    {
        $now = Carbon::parse('+ 1month');

        $this->expectException(InvalidRatingMonthException::class);

        new Month($now->year, $now->month);
    }
}
