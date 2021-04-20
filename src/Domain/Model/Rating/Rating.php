<?php


namespace Domain\Model\Rating;


use Doctrine\Common\Collections\ArrayCollection;
use Domain\Exceptions\NotFoundEntityException;
use Domain\Model\Assessment\Assessment;
use Domain\Model\Assessment\AssessmentId;
use Domain\Model\Assessment\Check;
use Domain\Model\Rating\Exceptions\InvalidRatingMonthException;
use Domain\Model\Rating\Exceptions\MaxReviewsForMonthReachedException;
use Domain\Model\Rating\Exceptions\ModificationRatingException;
use Domain\Model\User\UserId;

final class Rating
{
    const ALLOWED_REVIEWS_AMOUNT = 10;

    private ArrayCollection $assessments;

    private Month $month;

    private ?float $scored = null;

    private ?float $total = null;

    private UserId $userId;

    private Status $status;

    private RatingId $ratingId;

    public function __construct(RatingId $ratingId, UserId $userId, Month $date)
    {
        $this->assessments = new ArrayCollection();
        $this->month = $date;
        $this->userId = $userId;
        $this->status = new Status(Status::UNCOMPLETED);
        $this->ratingId = $ratingId;
    }

    /**
     * @param AssessmentId $assessmentId
     * @param Check $check
     * @param array $efficiencies
     * @return Assessment
     * @throws InvalidRatingMonthException
     * @throws MaxReviewsForMonthReachedException
     */
    public function addReview(AssessmentId $assessmentId,
                              Check $check,
                              array $efficiencies): Assessment
    {
        if ($this->isCompleted()) {
            throw new MaxReviewsForMonthReachedException();
        }

        if (!$this->month->isDateBetween((string)$check->getServiceDate())) {
            throw new InvalidRatingMonthException('Check service date must be between assessment date');
        }

        $assessment = new Assessment($assessmentId, $check, $efficiencies);
        $this->assessments->add($assessment);

        if ($this->isMaxReviewsAdded()) {
            $this->generateScore();
            $this->status = new Status(Status::COMPLETED);
        }

        return $assessment;
    }

    /**
     * @param AssessmentId $reviewId
     * @throws ModificationRatingException|NotFoundEntityException
     */
    public function removeReview(AssessmentId $reviewId)
    {
        if ($this->isCompleted()) {
            throw new ModificationRatingException('Cannot remove review from completed assessment');
        }

        foreach ($this->assessments as $k => $assessment) {
            if ($reviewId->isEqual($assessment->getId())) {
                unset($this->assessments[$k]);
                break;
            }
        }
    }

    /**
     * @param AssessmentId $reviewId
     * @param Check $check
     * @param array $criteria
     * @throws ModificationRatingException
     */
    public function editReview(AssessmentId $reviewId, Check $check, array $criteria)
    {
        if ($this->isCompleted()) {
            throw new ModificationRatingException('Cannot update review from completed assessment');
        }

        foreach ($this->assessments as $review) {
            if ($reviewId->isEqual($review->getId())) {
                $review->edit($check, $criteria);
                break;
            }
        }
    }

    public function isMaxReviewsAdded(): bool
    {
        return self::ALLOWED_REVIEWS_AMOUNT === $this->getAssessmentsCount();
    }

    public function isCompleted(): bool
    {
        return  $this->isMaxReviewsAdded() && $this->status->isEqualTo(Status::COMPLETED);
    }

    private function generateScore(): void
    {
        $this->scored = array_reduce($this->assessments->toArray(),
            fn($carry, Assessment $item) => $carry + $item->getScoredPoints(), 0);

        $this->total = array_reduce($this->assessments->toArray(),
            fn($carry, Assessment $item) => $carry + $item->getTotalPoints(),
            0);
    }

    public function getId(): RatingId
    {
        return $this->ratingId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getScored(): ?float
    {
        return $this->scored;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function getAssessmentsCount(): int
    {
        return $this->getAssessments()->count();
    }

    public function getAssessments(): ArrayCollection
    {
        return $this->assessments;
    }

    public function getMonth(): Month
    {
        return $this->month;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}
