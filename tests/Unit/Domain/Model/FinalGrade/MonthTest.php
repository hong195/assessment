<?php


namespace Tests\Unit\Domain\Model\FinalGrade;


use Carbon\Carbon;
use App\Domain\Model\FinalGrade\Month;
use App\Exceptions\InvalidRatingMonthException;
use PHPUnit\Framework\TestCase;

class MonthTest extends TestCase
{
    public function test_cannot_create_rating_with_future_date()
    {
        $futureDate = Carbon::parse('+ 1month');

        $this->expectException(InvalidRatingMonthException::class);

        new Month(new \DateTime($futureDate->format('Y-m-d')));
    }

    public function test_expects_exception_when_year_is_less_than_2019()
    {
        $this->expectException(InvalidRatingMonthException::class);

        new Month(new \DateTime('2019-3-10'));
    }
}
