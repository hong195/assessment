<?php


namespace Tests\Unit\Domain\Model\Builders;


use Carbon\Carbon;
use Domain\Model\Assessment\Assessment;
use Domain\Model\Assessment\AssessmentId;
use Domain\Model\Assessment\Month;
use Domain\Model\Review\Criterion;
use Domain\Model\Review\Option;
use Domain\Model\Review\ReviewId;
use Domain\Model\Review\ServiceDate;
use Domain\Model\User\UserId;

class AssessmentBuilder
{
    protected Month $month;

    protected UserId $userId;

    protected AssessmentId $assessmentId;

    /**
     * AssessmentBuilder constructor.
     * @throws \Domain\Model\Assessment\Exceptions\InvalidAssessmentMonthException
     */
    public function __construct()
    {
        $this->assessmentId = new AssessmentId(AssessmentId::next());
        $this->userId = new UserId(UserId::next());
        $this->month = new Month(now()->year, now()->month);
    }

    public static function anAssessment(): AssessmentBuilder
    {
        return new self();
    }

    public function withUserId(UserId $userId): AssessmentBuilder
    {
        $this->userId = $userId;
        return $this;
    }

    public function withMonth(Month $month): AssessmentBuilder
    {
        $this->month = $month;
        return $this;
    }

    public function build($reviewsNumber = 0): Assessment
    {
        $assessment = new Assessment($this->assessmentId, $this->userId, $this->month);
        $reviewerId = UserBuilder::aUser()->build()->getId();
        $reviewId = new ReviewId(ReviewId::next());
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

                $assessment->addReview($reviewId, $this->userId, $reviewerId, $check, $criteria);
            }
        }

        return $assessment;
    }
}
