<?php


namespace Tests\Unit\Domain\Model\Rating;


use Carbon\Carbon;
use Domain\Model\Assessment\AssessmentId;
use Domain\Model\Assessment\Efficiency;
use Domain\Model\Assessment\Option;
use Domain\Model\Assessment\ServiceDate;
use Domain\Model\Rating\Exceptions\InvalidratingMonthException;
use Domain\Model\Rating\Exceptions\MaxReviewsForMonthReachedException;
use Domain\Model\Rating\Exceptions\ModificationratingException;
use Domain\Model\Rating\Month;
use Domain\Model\Rating\Rating;
use Domain\Model\Rating\Status;
use Domain\Model\User\UserId;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Domain\Model\Builders\AssessmentBuilder;
use Tests\Unit\Domain\Model\Builders\CheckBuilder;
use Tests\Unit\Domain\Model\Builders\RatingBuilder;
use Tests\Unit\Domain\Model\Builders\UserBuilder;

class RatingTest extends TestCase
{
    private UserId $userId;

    private AssessmentId $reviewId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userId = UserBuilder::aUser()->build()->getId();
        $this->reviewId = AssessmentBuilder::aReview()->build()->getId();
    }

    public function test_is_max_reviews_added()
    {
        $rating = RatingBuilder::aRating()->build(10);

        $this->assertTrue($rating->isMaxReviewsAdded());
        $this->assertTrue($rating->isCompleted());
        $this->assertEquals(Rating::ALLOWED_REVIEWS_AMOUNT, $rating->getAssessmentsCount());
    }

    public function test_can_add_a_review_to_uncompleted_rating()
    {
        $now = now();
        $currentMonth = new Month($now->year, $now->month);
        $rating = RatingBuilder::aRating()->withUserId($this->userId)->withMonth($currentMonth)->build();

        $serviceDate = new ServiceDate($now->year, $now->month, $now->day);
        $check = CheckBuilder::aCheck()->withServiceDate($serviceDate)->build();

        $rating->addReview($this->reviewId, $check, []);

        $this->assertCount(1, $rating->getAssessments());
        $this->assertEquals(Status::UNCOMPLETED, $rating->getStatus());
    }

    public function test_can_add_only_10_reviews()
    {
        $rating = RatingBuilder::aRating()->withUserId($this->userId)->build(10);

        $this->expectException(MaxReviewsForMonthReachedException::class);

        $rating->addReview(
            $this->reviewId,
            CheckBuilder::aCheck()->build(),
            []);
    }

    public function test_fails_when_check_service_date_is_not_between_rating_month()
    {
        $aMonthAgo = Carbon::parse('-1 month');
        $ratingMonth = new Month($aMonthAgo->year, $aMonthAgo->month);

        $rating = RatingBuilder::aRating()
            ->withUserId($this->userId)
            ->withMonth($ratingMonth)
            ->build();

        $outDatedCheck = CheckBuilder::aCheck()->withServiceDate(
            new ServiceDate(now()->year, now()->month, now()->day)
        )
            ->build();

        $this->expectException(InvalidratingMonthException::class);

        $rating->addReview($this->reviewId, $outDatedCheck, []);
    }

    public function test_can_remove_a_review_from_uncompleted_rating()
    {
        $rating = RatingBuilder::aRating()->withUserId($this->userId)->build();
        $rating->addReview(
            $this->reviewId,
            CheckBuilder::aCheck()->build(),
            []);

        $rating->removeReview($this->reviewId);

        $this->assertEmpty($rating->getAssessments());
    }

    public function test_cannot_remove_review_from_completed_rating()
    {
        $rating = RatingBuilder::aRating()->withUserId($this->userId)->build(9);
        $rating->addReview(
            $this->reviewId,
            CheckBuilder::aCheck()->build(),
            []);

        $this->expectException(ModificationratingException::class);

        $rating->removeReview($this->reviewId);
    }

    public function test_cannot_edit_review_from_completed_rating()
    {
        $rating = RatingBuilder::aRating()->withUserId($this->userId)->build(9);
        $rating->addReview(
            $this->reviewId,
            CheckBuilder::aCheck()->build(),
            []);

        $this->expectException(ModificationratingException::class);

        $rating->editReview($this->reviewId, CheckBuilder::aCheck()->build(), []);
    }

    public function test_rating_completed_after_adding_10_reviews_for_one_month()
    {
        $efficiency = [
            new Efficiency('Этика',
                [
                    new Option('да', 1),
                    new Option('нет', 0)
                ],
                'да'
            ),
            new Efficiency('Доброжелательность',
                [
                    new Option('да', 0.5),
                    new Option('нет', 0)
                ],
                'да'
            ),
        ];
        $rating = RatingBuilder::aRating()->withUserId($this->userId)->build();

        foreach (range(1, Rating::ALLOWED_REVIEWS_AMOUNT) as $number) {
            $now = Carbon::parse("-$number hour");
            $serviceDate = new ServiceDate($now->year, $now->month, $now->day);
            $check = CheckBuilder::aCheck()->withServiceDate($serviceDate)->build();

            $rating->addReview(new AssessmentId(AssessmentId::next()), $check, $efficiency);
        }

        $this->assertEquals(Rating::ALLOWED_REVIEWS_AMOUNT, $rating->getAssessmentsCount());
        $this->assertEquals(15, $rating->getScored());
        $this->assertEquals(Status::COMPLETED, (string) $rating->getStatus());
        $this->assertTrue($rating->isCompleted());
    }
}
