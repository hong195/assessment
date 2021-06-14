<?php


namespace Tests\Unit\Domain\Model\EfficiencyAnalysis;


use Carbon\Carbon;
use Domain\Model\Assessment\AssessmentId;
use Domain\Model\Assessment\Criterion;
use Domain\Model\Assessment\Option;
use Domain\Model\Assessment\ServiceDate;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysis;
use Domain\Model\EfficiencyAnalysis\Exceptions\InvalidratingMonthException;
use Domain\Model\EfficiencyAnalysis\Exceptions\MaxReviewsForMonthReachedException;
use Domain\Model\EfficiencyAnalysis\Exceptions\ModificationratingException;
use Domain\Model\EfficiencyAnalysis\Month;
use Domain\Model\EfficiencyAnalysis\Status;
use Domain\Model\Employee\EmployeeId;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Domain\Model\Builders\AssessmentBuilder;
use Tests\Unit\Domain\Model\Builders\CheckBuilder;
use Tests\Unit\Domain\Model\Builders\EfficiencyAnalysisBuilder;

class EfficiencyAnalysisTest extends TestCase
{
    private EmployeeId $employeeId;

    private AssessmentId $assessmentId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->employeeId = new EmployeeId(EmployeeId::next());
        $this->assessmentId = AssessmentBuilder::aReview()->build()->getId();
    }

    public function test_is_max_reviews_added()
    {
        $efficiencyAnalysis = EfficiencyAnalysisBuilder::anAnalysis()->build(10);

        $this->assertTrue($efficiencyAnalysis->isMaxReviewsAdded());
        $this->assertTrue($efficiencyAnalysis->isCompleted());
        $this->assertEquals(EfficiencyAnalysis::ALLOWED_REVIEWS_AMOUNT, $efficiencyAnalysis->getAssessmentsCount());
    }

    public function test_can_add_a_review_to_uncompleted_rating()
    {
        $now = now();
        $currentMonth = new Month($now->year, $now->month);
        $efficiencyAnalysis = EfficiencyAnalysisBuilder::anAnalysis()
            ->withEmployee($this->employeeId)
            ->withMonth($currentMonth)
            ->build();

        $serviceDate = (new \DateTime())->setDate($now->year, $now->month, $now->day);
        $check = CheckBuilder::aCheck()->withServiceDate($serviceDate)->build();

        $efficiencyAnalysis->addAssessment($this->assessmentId, $check, []);

        $this->assertCount(1, $efficiencyAnalysis->getAssessments());
        $this->assertEquals(Status::UNCOMPLETED, $efficiencyAnalysis->getStatus());
    }

    public function test_can_add_only_10_reviews()
    {
        $rating = EfficiencyAnalysisBuilder::anAnalysis()->withEmployee($this->employeeId)->build(10);

        $this->expectException(MaxReviewsForMonthReachedException::class);

        $rating->addAssessment(
            $this->assessmentId,
            CheckBuilder::aCheck()->build(),
            []);
    }

    public function test_fails_when_check_service_date_is_not_between_rating_month()
    {
        $aMonthAgo = Carbon::parse('-1 month');
        $ratingMonth = new Month($aMonthAgo->year, $aMonthAgo->month);

        $rating = EfficiencyAnalysisBuilder::anAnalysis()
            ->withEmployee($this->employeeId)
            ->withMonth($ratingMonth)
            ->build();

        $outDatedCheck = CheckBuilder::aCheck()->withServiceDate(
            (new \DateTime())->setDate(now()->year, now()->month, now()->day)
        )
            ->build();

        $this->expectException(InvalidratingMonthException::class);

        $rating->addAssessment($this->assessmentId, $outDatedCheck, []);
    }

    public function test_can_remove_a_review_from_uncompleted_rating()
    {
        $rating = EfficiencyAnalysisBuilder::anAnalysis()->withEmployee($this->employeeId)->build();
        $rating->addAssessment(
            $this->assessmentId,
            CheckBuilder::aCheck()->build(),
            []);

        $rating->removeAssessment($this->assessmentId);

        $this->assertEmpty($rating->getAssessments());
    }

    public function test_cannot_remove_review_from_completed_rating()
    {
        $rating = EfficiencyAnalysisBuilder::anAnalysis()->withEmployee($this->employeeId)->build(9);
        $rating->addAssessment(
            $this->assessmentId,
            CheckBuilder::aCheck()->build(),
            []);

        $this->expectException(ModificationratingException::class);

        $rating->removeAssessment($this->assessmentId);
    }

    public function test_cannot_edit_review_from_completed_analysis()
    {
        $rating = EfficiencyAnalysisBuilder::anAnalysis()->withEmployee($this->employeeId)->build(9);
        $rating->addAssessment(
            $this->assessmentId,
            CheckBuilder::aCheck()->build(),
            []);

        $this->expectException(ModificationratingException::class);

        $rating->editReview($this->assessmentId, CheckBuilder::aCheck()->build(), []);
    }

    public function test_analysis_completed_after_adding_10_reviews_for_one_month()
    {
        $efficiency = [
            new Criterion('Этика',
                [
                    new Option('да', 1),
                    new Option('нет', 0)
                ],
                'да'
            ),
            new Criterion('Доброжелательность',
                [
                    new Option('да', 0.5),
                    new Option('нет', 0)
                ],
                'да'
            ),
        ];
        $rating = EfficiencyAnalysisBuilder::anAnalysis()->withEmployee($this->employeeId)->build();

        foreach (range(1, EfficiencyAnalysis::ALLOWED_REVIEWS_AMOUNT) as $number) {
            $now = Carbon::parse("-$number hour");
            $serviceDate = (new \DateTime())->setDate($now->year, $now->month, $now->day);
            $check = CheckBuilder::aCheck()->withServiceDate($serviceDate)->build();

            $rating->addAssessment(new AssessmentId(AssessmentId::next()), $check, $efficiency);
        }

        $this->assertEquals(EfficiencyAnalysis::ALLOWED_REVIEWS_AMOUNT, $rating->getAssessmentsCount());
        $this->assertEquals(15, $rating->getScored());
        $this->assertEquals(Status::COMPLETED, (string)$rating->getStatus());
        $this->assertTrue($rating->isCompleted());
    }
}
