<?php


namespace Tests\Unit\Domain\Model\Builders;


use Domain\Model\Assessment\Check;
use Domain\Model\Assessment\Assessment;
use Domain\Model\Assessment\AssessmentId;
use Domain\Model\User\UserId;

class ReviewBuilder
{

    private Check $check;

    private UserId $userId;

    private UserId $reviewerId;
    /**
     * @var AssessmentId
     */
    private AssessmentId $id;

    private array $efficiencies;

    public function __construct()
    {
        $this->id = new AssessmentId(AssessmentId::next());
        $this->check = CheckBuilder::aCheck()->build();
        $this->userId = new UserId(UserId::next());
        $this->reviewerId = new UserId(UserId::next());
        $this->efficiencies = [];
    }

    public static function aReview(): ReviewBuilder
    {
        return new self();
    }

    public function withId(AssessmentId $id) : self
    {
        $this->id = $id;
        return $this;
    }

    public function withReviewerId(UserId $userId) : self
    {
        $this->reviewerId = $userId;
        return $this;
    }

    public function withEfficiencies(array $efficiency): ReviewBuilder
    {
        $this->efficiencies = $efficiency;
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

    public function build(): Assessment
    {
        return new Assessment($this->id, $this->userId, $this->reviewerId, $this->check, $this->efficiencies);
    }
}
