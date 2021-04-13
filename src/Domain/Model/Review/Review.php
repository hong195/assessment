<?php


namespace Domain\Model\Review;


use Doctrine\Common\Collections\ArrayCollection;
use Domain\Exceptions\DomainException;
use Domain\Model\Review\Exceptions\ReviewerEqualsToPharmacistException;
use Domain\Model\User\UserId;

final class Review
{
    /**
     * @var UserId
     */
    private UserId $userId;
    /**
     * @var UserId
     */
    private UserId $reviewerId;
    /**
     * @var Check
     */
    private Check $check;

    private ArrayCollection $criteria;
    /**
     * @var ReviewId
     */
    private ReviewId $id;

    /**
     * Review constructor.
     * @param ReviewId $id
     * @param UserId $userId
     * @param UserId $reviewerId
     * @param Check $check
     * @param array $criteria
     * @throws DomainException
     */
    public function __construct(ReviewId $id,
                                UserId $userId,
                                UserId $reviewerId,
                                Check $check,
                                array $criteria)
    {
        $this->assertUserIdNotEqualsToReviewerId($userId, $reviewerId);
        $this->id = $id;
        $this->userId = $userId;
        $this->reviewerId = $reviewerId;
        $this->check = $check;
        $this->criteria = new ArrayCollection($criteria);
    }

    /**
     * @param UserId $userId
     * @param UserId $reviewerId
     * @throws ReviewerEqualsToPharmacistException
     */
    private function assertUserIdNotEqualsToReviewerId(UserId $userId, UserId $reviewerId)
    {
        if ($reviewerId->isEqual($userId)) {
            throw new ReviewerEqualsToPharmacistException('A user cannot review himself');
        }
    }

    public function edit(UserId $userId, UserId $reviewerId, Check $check, array $criteria)
    {
        $this->userId = $userId;
        $this->reviewerId = $reviewerId;
        $this->check = $check;
        $this->criteria = new ArrayCollection($criteria);
    }

    public function getId(): ReviewId
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getReviewerId(): UserId
    {
        return $this->reviewerId;
    }

    public function getCheck(): Check
    {
        return $this->check;
    }

    public function getCriteria(): ArrayCollection
    {
        return $this->criteria;
    }

    /**
     * @return float
     * @throws \Domain\Model\Assessment\Exceptions\NotExistingSelectedOptionException
     */
    public function getScoredPoints(): float
    {
        return array_reduce($this->criteria->toArray(), function ($prev, Criterion $criterion) {
            return $prev + $criterion->getSelectedValue();
        }, 0);
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->criteria as $criterion) {
            $total  +=  array_reduce($criterion->getOptions()->toArray(),function($carry, $option) {
                $carry = max($carry, $option->getValue());
                return $carry;
            }, 0);
        }

        return $total;
    }
}
