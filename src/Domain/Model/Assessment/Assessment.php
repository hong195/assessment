<?php


namespace Domain\Model\Assessment;


use Doctrine\Common\Collections\ArrayCollection;
use Domain\Model\Participant\Reviewer;

final class Assessment
{
    private Reviewer $reviewer;

    private Check $check;

    private ArrayCollection $efficiencies;

    private AssessmentId $id;

    /**
     * assessment constructor.
     * @param AssessmentId $id
     * @param Check $check
     * @param Criterion[] $efficiencies
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

    public function getReviewer(): Reviewer
    {
        return $this->reviewer;
    }

    public function getCheck(): Check
    {
        return $this->check;
    }

    public function getEfficiencies(): ArrayCollection
    {
        return $this->efficiencies;
    }

    public function assignReviewer(Reviewer $reviewer)
    {
        $this->reviewer = $reviewer;
    }
    /**
     * @return float
     * @throws Exceptions\NotExistingSelectedOptionException
     */
    public function getScoredPoints(): float
    {
        return array_reduce($this->efficiencies->toArray(), function ($prev, Criterion $criterion) {
            return $prev + $criterion->getSelectedValue();
        }, 0);
    }

    public function getTotalPoints(): float
    {
        return array_reduce($this->efficiencies->toArray(), function ($carry, Criterion $efficiency) {
            return $carry + $efficiency->getMaxPoint();
        },0);
    }
}
