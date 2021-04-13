<?php


namespace Tests\Unit\Domain\Model\Assessment;


use Carbon\Carbon;
use Domain\Model\Assessment\Assessment;
use Domain\Model\Assessment\Exceptions\ModificationAssessmentException;
use Domain\Model\Assessment\Exceptions\DifferentAssessmentUserException;
use Domain\Model\Assessment\Exceptions\InvalidAssessmentMonthException;
use Domain\Model\Assessment\Exceptions\MaximumReviewsForMonthReachedException;
use Domain\Model\Assessment\Month;
use Domain\Model\Assessment\Status;
use Domain\Model\Review\Criterion;
use Domain\Model\Review\Option;
use Domain\Model\Review\ReviewId;
use Domain\Model\Review\ServiceDate;
use Domain\Model\User\UserId;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Domain\Model\Builders\AssessmentBuilder;
use Tests\Unit\Domain\Model\Builders\CheckBuilder;
use Tests\Unit\Domain\Model\Builders\ReviewBuilder;
use Tests\Unit\Domain\Model\Builders\UserBuilder;

class AssessmentTest extends TestCase
{
    private UserId $userId;

    private UserId $reviewerId;
    /**
     * @var ReviewId
     */
    private ReviewId $reviewId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userId = UserBuilder::aUser()->build()->getId();
        $this->reviewerId = UserBuilder::aUser()->build()->getId();
        $this->reviewId = ReviewBuilder::aReview()->build()->getId();
    }

    public function test_can_add_a_review_to_uncompleted_assessment()
    {
        $now = now();
        $currentMonth = new Month($now->year, $now->month);
        $assessment = AssessmentBuilder::anAssessment()->withUserId($this->userId)->withMonth($currentMonth)->build();

        $serviceDate = new ServiceDate($now->year, $now->month, $now->day);
        $check = CheckBuilder::aCheck()->withServiceDate($serviceDate)->build();

        $assessment->addReview($this->reviewId, $this->userId, $this->reviewerId, $check, []);

        $this->assertCount(1, $assessment->getReviews());
        $this->assertEquals(Status::UNCOMPLETED, $assessment->getStatus());
    }

    public function test_can_add_only_10_reviews()
    {
        $assessment = AssessmentBuilder::anAssessment()->withUserId($this->userId)->build(10);

        $this->expectException(MaximumReviewsForMonthReachedException::class);

        $assessment->addReview(
            $this->reviewId,
            $this->userId,
            $this->reviewerId,
            CheckBuilder::aCheck()->build(),
            []);
    }

    public function test_fails_when_check_service_date_is_not_between_assessment_month()
    {
        $aMonthAgo = Carbon::parse('-1 month');
        $assessmentMonth = new Month($aMonthAgo->year, $aMonthAgo->month);

        $assessment = AssessmentBuilder::anAssessment()
            ->withUserId($this->userId)
            ->withMonth($assessmentMonth)
            ->build();

        $outDatedCheck = CheckBuilder::aCheck()->withServiceDate(
            new ServiceDate(now()->year, now()->month, now()->day)
        )
            ->build();

        $this->expectException(InvalidAssessmentMonthException::class);

        $assessment->addReview($this->reviewId, $this->userId, $this->reviewerId, $outDatedCheck, []);
    }

    public function test_cannot_assign_review_of_different_user()
    {
        $anotherUserId = new UserId(UserId::next());
        $check = CheckBuilder::aCheck()->build();
        $assessment = AssessmentBuilder::anAssessment()->withUserId($this->userId)->build();

        $this->expectException(DifferentAssessmentUserException::class);

        $assessment->addReview($this->reviewId, $anotherUserId, $this->reviewerId, $check, []);
    }

    public function test_can_remove_a_review_from_uncompleted_assessment()
    {
        $assessment = AssessmentBuilder::anAssessment()->withUserId($this->userId)->build();
        $assessment->addReview(
            $this->reviewId,
            $this->userId,
            new UserId(UserId::next()),
            CheckBuilder::aCheck()->build(),
            []);

        $assessment->removeReview($this->reviewId);

        $this->assertEmpty($assessment->getReviews());
    }

    public function test_cannot_remove_review_from_completed_assessment()
    {
        $assessment = AssessmentBuilder::anAssessment()->withUserId($this->userId)->build(9);
        $assessment->addReview(
            $this->reviewId,
            $this->userId,
            new UserId(UserId::next()),
            CheckBuilder::aCheck()->build(),
            []);

        $this->expectException(ModificationAssessmentException::class);

        $assessment->removeReview($this->reviewId);
    }

    public function test_cannot_update_review_from_completed_assessment()
    {
        $assessment = AssessmentBuilder::anAssessment()->withUserId($this->userId)->build(9);
        $assessment->addReview(
            $this->reviewId,
            $this->userId,
            new UserId(UserId::next()),
            CheckBuilder::aCheck()->build(),
            []);

        $this->expectException(ModificationAssessmentException::class);

        $assessment->updateReview($this->reviewId, $this->reviewerId, CheckBuilder::aCheck()->build(), []);
    }

    public function test_assessment_completed_after_adding_10_reviews_for_one_month()
    {
        $criteria = [
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
        $assessment = AssessmentBuilder::anAssessment()->withUserId($this->userId)->build();

        foreach (range(1, Assessment::ALLOWED_REVIEWS_AMOUNT) as $number) {
            $now = Carbon::parse("-$number hour");
            $serviceDate = new ServiceDate($now->year, $now->month, $now->day);
            $check = CheckBuilder::aCheck()->withServiceDate($serviceDate)->build();

            $assessment->addReview(new ReviewId(ReviewId::next()), $this->userId, $this->reviewerId, $check, $criteria);
        }

        $this->assertEquals(Assessment::ALLOWED_REVIEWS_AMOUNT, $assessment->getReviewsCount());
        $this->assertEquals(15, $assessment->getRating());
        $this->assertEquals(Status::COMPLETED, (string)$assessment->getStatus());
        $this->assertTrue($assessment->isCompleted());
    }
}
