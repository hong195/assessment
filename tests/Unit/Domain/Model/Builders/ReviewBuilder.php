<?php


namespace Tests\Unit\Domain\Model\Builders;


use Domain\Model\Review\Check;
use Domain\Model\Review\Review;
use Domain\Model\Review\ReviewId;
use Domain\Model\User\UserId;

class ReviewBuilder
{

    private Check $check;

    private UserId $userId;

    private UserId $reviewerId;
    /**
     * @var ReviewId
     */
    private ReviewId $id;

    private array $criteria;

    public function __construct()
    {
        $this->id = new ReviewId(ReviewId::next());
        $this->check = CheckBuilder::aCheck()->build();
        $this->userId = new UserId(UserId::next());
        $this->reviewerId = new UserId(UserId::next());
        $this->criteria = [];
    }

    public static function aReview(): ReviewBuilder
    {
        return new self();
    }

    public function withId(ReviewId $id) : self
    {
        $this->id = $id;
        return $this;
    }

    public function withReviewerId(UserId $userId) : self
    {
        $this->reviewerId = $userId;
        return $this;
    }

    public function withCriteria(array $criteria): ReviewBuilder
    {
        $this->criteria = $criteria;
        return $this;
    }

    public function withUserId(UserId $userId) : self
    {
        $this->userId = $userId;
        return $this;
    }

    public function withCheck(Check $check): ReviewBuilder
    {
        $this->check = $check;

        return $this;
    }

    public function build(): Review
    {
        return new Review($this->id, $this->userId, $this->reviewerId, $this->check, $this->criteria);
    }
}
