<?php


namespace Tests\Unit\Domain\Model\Assessment;


use Carbon\Carbon;
use Domain\Model\Assessment\Month;
use Domain\Model\Assessment\Exceptions\InvalidAssessmentMonthException;
use PHPUnit\Framework\TestCase;

class MonthTest extends TestCase
{
    public function test_cannot_create_assessment_with_future_date()
    {
        $now = Carbon::parse('+ 1month');

        $this->expectException(InvalidAssessmentMonthException::class);

        new Month($now->year, $now->month);
    }
}
