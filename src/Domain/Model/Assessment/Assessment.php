<?php


namespace Domain\Model\Assessment;


use Doctrine\Common\Collections\ArrayCollection;
use Domain\Model\User\UserId;

final class Assessment
{
    private UserId $reviewerId;

    private Check $check;

    private ArrayCollection $efficiencies;

    private AssessmentId $id;

    /**
     * assessment constructor.
     * @param AssessmentId $id
     * @param UserId $reviewerId
     * @param Check $check
     * @param Efficiency[] $efficiencies
     */
    public function __construct(AssessmentId $id,
                                Check $check,
                                array $efficiencies)
    {
        $this->id = $id;
        $this->check = $check;
        $this->efficiencies = new ArrayCollection($efficiencies);
    }

    public function edit(Check $check, array $criteria)
    {
        $this->check = $check;
        $this->efficiencies = new ArrayCollection($criteria);
    }

    public function getId(): AssessmentId
    {
        return $this->id;
    }

    public function getReviewerId(): UserId
    {
        return $this->reviewerId;
    }

    public function getCheck(): Check
    {
        return $this->check;
    }

    public function getEfficiencies(): ArrayCollection
    {
        return $this->efficiencies;
    }

    public function setReviewerId(UserId $reviewerId)
    {
        $this->reviewerId = $reviewerId;
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
