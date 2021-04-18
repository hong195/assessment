<?php


namespace Domain\Model\Assessment;


use Doctrine\Common\Collections\ArrayCollection;
use Domain\Exceptions\DomainException;
use Domain\Model\Assessment\Exceptions\TheSameUserException;
use Domain\Model\User\UserId;

final class Assessment
{
    /**
     * @var UserId
     */
    private UserId $userId;
    /**
     * @var UserId
     */
    private UserId $assessmenterId;
    /**
     * @var Check
     */
    private Check $check;

    private ArrayCollection $efficiencies;
    /**
     * @var AssessmentId
     */
    private AssessmentId $id;

    /**
     * assessment constructor.
     * @param AssessmentId $id
     * @param UserId $userId
     * @param UserId $assessmenterId
     * @param Check $check
     * @param Efficiency[] $efficiencies
     * @throws DomainException
     */
    public function __construct(AssessmentId $id,
                                UserId $userId,
                                UserId $assessmenterId,
                                Check $check,
                                array $efficiencies)
    {
        $this->assertUserIdNotEqualsToassessmenterId($userId, $assessmenterId);
        $this->id = $id;
        $this->userId = $userId;
        $this->assessmenterId = $assessmenterId;
        $this->check = $check;
        $this->efficiencies = new ArrayCollection($efficiencies);
    }

    /**
     * @param UserId $userId
     * @param UserId $assessmenterId
     * @throws TheSameUserException
     */
    private function assertUserIdNotEqualsToassessmenterId(UserId $userId, UserId $assessmenterId)
    {
        if ($assessmenterId->isEqual($userId)) {
            throw new TheSameUserException('A user cannot assessment himself');
        }
    }

    public function edit(UserId $assessmenterId, Check $check, array $criteria)
    {
        $this->assessmenterId = $assessmenterId;
        $this->check = $check;
        $this->efficiencies = new ArrayCollection($criteria);
    }

    public function getId(): AssessmentId
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getassessmenterId(): UserId
    {
        return $this->assessmenterId;
    }

    public function getCheck(): Check
    {
        return $this->check;
    }

    public function getEfficiencies(): ArrayCollection
    {
        return $this->efficiencies;
    }

    /**
     * @return float
     * @throws Exceptions\NotExistingSelectedOptionException
     */
    public function getScoredPoints(): float
    {
        return array_reduce($this->efficiencies->toArray(), function ($prev, Efficiency $efficiency) {
            return $prev + $efficiency->getSelectedValue();
        }, 0);
    }

    public function getTotalPoints(): float
    {
        return array_reduce($this->efficiencies->toArray(), function ($carry, Efficiency $efficiency) {
            return $carry + $efficiency->getMaxPoint();
        },0);
    }
}
