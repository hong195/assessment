<?php


namespace Domain\Model\Rating;


use Doctrine\Common\Collections\ArrayCollection;
use Domain\Exceptions\NotFoundEntityException;
use Domain\Model\Rating\Exceptions\ModificationRatingException;
use Domain\Model\Rating\Exceptions\DifferentRatingUserException;
use Domain\Model\Rating\Exceptions\InvalidRatingMonthException;
use Domain\Model\Rating\Exceptions\MaxReviewsForMonthReachedException;
use Domain\Model\Assessment\Check;
use Domain\Model\Assessment\Assessment;
use Domain\Model\Assessment\AssessmentId;
use Domain\Model\User\UserId;

final class Rating
{
    const ALLOWED_REVIEWS_AMOUNT = 10;

    private ArrayCollection $reviews;

    private Month $month;

    private ?float $scored = null;

    private ?float $total = null;

    private UserId $userId;

    private Status $status;

    private RatingId $ratingId;

    public function __construct(RatingId $ratingId, UserId $userId, Month $date)
    {
        $this->reviews = new ArrayCollection();
        $this->month = $date;
        $this->userId = $userId;
        $this->status = new Status(Status::UNCOMPLETED);
        $this->ratingId = $ratingId;
    }

    /**
     * @param AssessmentId $reviewId
     * @param UserId $userId
     * @param UserId $reviewerId
     * @param Check $check
     * @param array $efficiencies
     * @return Assessment
     * @throws DifferentRatingUserException
     * @throws InvalidRatingMonthException
     * @throws MaxReviewsForMonthReachedException
     * @throws \Domain\Exceptions\DomainException
     */
    public function addReview(AssessmentId $reviewId,
                              UserId $userId,
                              UserId $reviewerId,
                              Check $check,
                              array $efficiencies): Assessment
    {
        if ($this->isCompleted()) {
            throw new MaxReviewsForMonthReachedException();
        }

        if (!$userId->isEqual($this->userId)) {
            throw new DifferentRatingUserException('Cannot assign a review of different user');
        }

        if (!$this->month->isDateBetween((string)$check->getServiceDate())) {
            throw new InvalidRatingMonthException('Check service date must be between assessment date');
        }

        $review = new Assessment($reviewId, $userId, $reviewerId, $check, $efficiencies);
        $this->reviews->add($review);

        if ($this->isMaxReviewsAdded()) {
            $this->generateScore();
            $this->status = new Status(Status::COMPLETED);
        }

        return $review;
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

        foreach ($this->reviews as $k => $review) {
            if ($reviewId->isEqual($review->getId())) {
                unset($this->reviews[$k]);
                break;
            }
        }
    }

    /**
     * @param AssessmentId $reviewId
     * @param UserId $reviewerId
     * @param Check $check
     * @param array $criteria
     * @throws ModificationRatingException
     */
    public function updateReview(AssessmentId $reviewId, UserId $reviewerId, Check $check, array $criteria)
    {
        if ($this->isCompleted()) {
            throw new ModificationRatingException('Cannot update review from completed assessment');
        }

        foreach ($this->reviews as $review) {
            if ($reviewId->isEqual($review->getId())) {
                $review->edit($reviewerId, $check, $criteria);
                break;
            }
        }
    }

    public function isMaxReviewsAdded(): bool
    {
        return self::ALLOWED_REVIEWS_AMOUNT === $this->getReviewsCount();
    }

    public function isCompleted(): bool
    {
        return  $this->isMaxReviewsAdded() && $this->status->isEqualTo(Status::COMPLETED);
    }

    private function generateScore(): void
    {
        $this->scored = array_reduce($this->reviews->toArray(),
            fn($carry, Assessment $item) => $carry + $item->getScoredPoints(), 0);

        $this->total = array_reduce($this->reviews->toArray(),
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

    public function getReviewsCount(): int
    {
        return $this->getReviews()->count();
    }

    public function getReviews(): ArrayCollection
    {
        return $this->reviews;
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
