<?php


namespace Domain\Model\Assessment;


use Doctrine\Common\Collections\ArrayCollection;
use Domain\Exceptions\NotFoundEntityException;
use Domain\Model\Assessment\Exceptions\ModificationAssessmentException;
use Domain\Model\Assessment\Exceptions\DifferentAssessmentUserException;
use Domain\Model\Assessment\Exceptions\InvalidAssessmentMonthException;
use Domain\Model\Assessment\Exceptions\MaximumReviewsForMonthReachedException;
use Domain\Model\Review\Check;
use Domain\Model\Review\Review;
use Domain\Model\Review\ReviewId;
use Domain\Model\User\UserId;

final class Assessment
{
    const ALLOWED_REVIEWS_AMOUNT = 10;

    private ArrayCollection $reviews;

    private Month $month;

    private ?float $rating;

    private ?float $total;

    private UserId $userId;

    private Status $status;

    private AssessmentId $assessmentId;

    public function __construct(AssessmentId $assessmentId, UserId $userId, Month $date)
    {
        $this->reviews = new ArrayCollection();
        $this->month = $date;
        $this->userId = $userId;
        $this->status = new Status(Status::UNCOMPLETED);
        $this->assessmentId = $assessmentId;
    }

    /**
     * @param ReviewId $reviewId
     * @param UserId $userId
     * @param UserId $reviewerId
     * @param Check $check
     * @param array $criteria
     * @return Review
     * @throws DifferentAssessmentUserException
     * @throws InvalidAssessmentMonthException
     * @throws MaximumReviewsForMonthReachedException
     * @throws \Domain\Exceptions\DomainException
     */
    public function addReview(ReviewId $reviewId,
                              UserId $userId,
                              UserId $reviewerId,
                              Check $check,
                              array $criteria): Review
    {
        if ($this->isCompleted()) {
            throw new MaximumReviewsForMonthReachedException();
        }

        if (!$userId->isEqual($this->userId)) {
            throw new DifferentAssessmentUserException('Cannot assign a review of different user');
        }

        if (!$this->month->isDateBetween((string)$check->getServiceDate())) {
            throw new InvalidAssessmentMonthException('Check service date must be between assessment date');
        }

        $review = new Review($reviewId, $userId, $reviewerId, $check, $criteria);
        $this->reviews->add($review);

        if ($this->getReviewsCount() === self::ALLOWED_REVIEWS_AMOUNT) {
            $this->complete();
            $this->status = new Status(Status::COMPLETED);
        }

        return $review;
    }

    /**
     * @param ReviewId $reviewId
     * @throws ModificationAssessmentException|NotFoundEntityException
     */
    public function removeReview(ReviewId $reviewId)
    {
        if ($this->isCompleted()) {
            throw new ModificationAssessmentException('Cannot remove review from completed assessment');
        }

        foreach ($this->reviews as $k => $review) {
            if ($reviewId->isEqual($review->getId())) {
                unset($this->reviews[$k]);
                break;
            }
        }
    }

    /**
     * @param ReviewId $reviewId
     * @param UserId $reviewerId
     * @param Check $check
     * @param array $criteria
     * @throws ModificationAssessmentException
     */
    public function updateReview(ReviewId $reviewId, UserId $reviewerId, Check $check, array $criteria)
    {
        if ($this->isCompleted()) {
            throw new ModificationAssessmentException('Cannot update review from completed assessment');
        }

        foreach ($this->reviews as $review) {
            if ($reviewId->isEqual($review->getId())) {
                $review->edit($reviewerId, $check, $criteria);
                break;
            }
        }
    }

    public function isCompleted(): bool
    {
        return $this->status->isEqualTo(Status::COMPLETED);
    }

    private function complete(): void
    {
        $this->rating = array_reduce($this->reviews->toArray(),
            fn($carry, Review $item) => $carry + $item->getScoredPoints(), 0);

        $this->total = array_reduce($this->reviews->toArray(),
            fn($carry, Review $item) => $carry + $item->getTotalPoints(),
            0);
    }

    public function getId(): AssessmentId
    {
        return $this->assessmentId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getRating(): ?float
    {
        return $this->rating;
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
