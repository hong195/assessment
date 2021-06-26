<?php


namespace Tests\Builders;


use App\Domain\Model\Assessment\AssessmentId;
use App\Domain\Model\Assessment\Criterion;
use App\Domain\Model\Assessment\Option;
use App\Domain\Model\Employee\EmployeeId;
use App\Domain\Model\FinalGrade\FinalGrade;
use App\Domain\Model\FinalGrade\FinalGradeId;
use App\Domain\Model\FinalGrade\Month;
use Carbon\Carbon;

class FinalGradeBuilder
{
    protected Month $month;

    protected EmployeeId $employeeId;

    protected FinalGradeId $ratingId;
    private FinalGradeId $analysesId;

    public function __construct()
    {
        $this->analysesId = FinalGradeId::next();
        $this->employeeId = new EmployeeId(EmployeeId::next());
        $this->month = new Month(new \DateTime(now()->format('Y-m-d')));
    }

    public static function anAnalysis(): FinalGradeBuilder
    {
        return new self();
    }

    public function withId(FinalGradeId $analysisId)
    {
        $this->analysesId = $analysisId;
        return $this;
    }
    public function withEmployee(EmployeeId $employeeId): FinalGradeBuilder
    {
        $this->employeeId = $employeeId;
        return $this;
    }

    public function withMonth(Month $month): FinalGradeBuilder
    {
        $this->month = $month;
        return $this;
    }

    public function build($reviewsNumber = 0): FinalGrade
    {
        $efficiencyAnalysis = new FinalGrade($this->analysesId, $this->employeeId, $this->month);
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
                $serviceDate = (new \DateTime())->setDate($now->year, $now->month, $now->day);
                $check = CheckBuilder::aCheck()->withServiceDate($serviceDate)->build();

                $efficiencyAnalysis->addAssessment($reviewId, $check, $criteria);
            }
        }

        return $efficiencyAnalysis;
    }
}
