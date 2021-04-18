<?php


namespace Tests\Unit\Domain\Model\Builders;


use Carbon\Carbon;
use Domain\Model\Rating\Rating;
use Domain\Model\Rating\RatingId;
use Domain\Model\Rating\Month;
use Domain\Model\Assessment\Efficiency;
use Domain\Model\Assessment\Option;
use Domain\Model\Assessment\AssessmentId;
use Domain\Model\Assessment\ServiceDate;
use Domain\Model\User\UserId;

class RatingBuilder
{
    protected Month $month;

    protected UserId $userId;

    protected RatingId $ratingId;

    /**
     * ratingBuilder constructor.
     * @throws \Domain\Model\Rating\Exceptions\InvalidratingMonthException
     */
    public function __construct()
    {
        $this->ratingId = new RatingId(RatingId::next());
        $this->userId = new UserId(UserId::next());
        $this->month = new Month(now()->year, now()->month);
    }

    public static function aRating(): RatingBuilder
    {
        return new self();
    }

    public function withUserId(UserId $userId): RatingBuilder
    {
        $this->userId = $userId;
        return $this;
    }

    public function withMonth(Month $month): RatingBuilder
    {
        $this->month = $month;
        return $this;
    }

    public function build($reviewsNumber = 0): Rating
    {
        $rating = new Rating($this->ratingId, $this->userId, $this->month);
        $reviewerId = UserBuilder::aUser()->build()->getId();
        $reviewId = new AssessmentId(AssessmentId::next());
        $criteria = [
            new Efficiency('Этика',
                [
                    new Option('да', 1), new Option('нет', 0)
                ],
                'да'
            ),
            new Efficiency('Доброжелательность', [
                new Option('да', 1), new Option('нет', 0)
            ], 'да'
            ),
        ];

        if ($reviewsNumber) {
            foreach (range(1, $reviewsNumber) as $number) {
                $now = Carbon::parse('-1 hour');
                $serviceDate = new ServiceDate($now->year, $now->month, $now->day);
                $check = CheckBuilder::aCheck()->withServiceDate($serviceDate)->build();

                $rating->addReview($reviewId, $this->userId, $reviewerId, $check, $criteria);
            }
        }

        return $rating;
    }
}
