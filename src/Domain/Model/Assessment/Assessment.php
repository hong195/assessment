<?php


namespace Domain\Model\Assessment;


use Doctrine\Common\Collections\ArrayCollection;

final class Assessment
{
    private WatcherId $watcherId;

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

    public function getWatcherId(): WatcherId
    {
        return $this->watcherId;
    }

    public function getCheck(): Check
    {
        return $this->check;
    }

    public function getEfficiencies(): ArrayCollection
    {
        return $this->efficiencies;
    }

    public function setWatcherId(WatcherId $watcherId)
    {
        $this->watcherId = $watcherId;
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
