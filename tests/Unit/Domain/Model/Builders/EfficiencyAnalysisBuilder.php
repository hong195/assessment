<?php


namespace Tests\Unit\Domain\Model\Builders;


use Carbon\Carbon;
use Domain\Model\Assessment\AssessmentId;
use Domain\Model\Assessment\Criterion;
use Domain\Model\Assessment\Option;
use Domain\Model\Assessment\ServiceDate;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysis;
use Domain\Model\EfficiencyAnalysis\EmployeeId;
use Domain\Model\EfficiencyAnalysis\Month;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisId;

class EfficiencyAnalysisBuilder
{
    protected Month $month;

    protected EmployeeId $employeeId;

    protected EfficiencyAnalysisId $ratingId;

    public function __construct()
    {
        $this->ratingId = new EfficiencyAnalysisId(EfficiencyAnalysisId::next());
        $this->employeeId = new EmployeeId(EmployeeId::next());
        $this->month = new Month(now()->year, now()->month);
    }

    public static function anAnalysis(): EfficiencyAnalysisBuilder
    {
        return new self();
    }

    public function withEmployee(EmployeeId $employeeId): EfficiencyAnalysisBuilder
    {
        $this->employeeId = $employeeId;
        return $this;
    }

    public function withMonth(Month $month): EfficiencyAnalysisBuilder
    {
        $this->month = $month;
        return $this;
    }

    public function build($reviewsNumber = 0): EfficiencyAnalysis
    {
        $efficiencyAnalysis = new EfficiencyAnalysis($this->ratingId, $this->employeeId, $this->month);
        $reviewId = new AssessmentId(AssessmentId::next());
        $criteria = [
            new Criterion('Этика',
                [
                    new Option('да', 1), new Option('нет', 0)
                ],
                'да'
            ),
            new Criterion('Доброжелательность', [
                new Option('да', 1), new Option('нет', 0)
            ], 'да'
            ),
        ];

        if ($reviewsNumber) {
            foreach (range(1, $reviewsNumber) as $number) {
                $now = Carbon::parse('-1 hour');
                $serviceDate = new ServiceDate($now->year, $now->month, $now->day);
                $check = CheckBuilder::aCheck()->withServiceDate($serviceDate)->build();

                $efficiencyAnalysis->addReview($reviewId, $check, $criteria);
            }
        }

        return $efficiencyAnalysis;
    }
}
