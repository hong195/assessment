<?php


namespace Domain\Model\Assessment;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysis;

/**
 * Class Assessment
 * @ORM\Entity
 */
final class Assessment
{
    /**
     * @ORM\Column (type="assessment_id")
     * @ORM\Id
     */
    private AssessmentId $id;
    /**
     * @ORM\ManyToOne(targetEntity="Domain\Model\EfficiencyAnalysis\EfficiencyAnalysis", inversedBy="assessments", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="analysis_id", referencedColumnName="id")
     */
    private EfficiencyAnalysis $analysis;
    /**
     * @ORM\Column (type="assessment_reviewer_id")
     */
    private ReviewerId $reviewerId;

    /**
     * @ORM\Embedded (class="Check")
     */
    private Check $check;

    /**
     * @ORM\Column (type="assessment_criteria", nullable=true)
     */
    private ArrayCollection $criteria;


    /**
     * Assessment constructor.
     * @param AssessmentId $id
     * @param EfficiencyAnalysis $analysis
     * @param Check $check
     * @param array $criteria
     */
    public function __construct(AssessmentId $id,
                                EfficiencyAnalysis $analysis,
                                Check $check,
                                array $criteria)
    {
        $this->analysis = $analysis;
        $this->id = $id;
        $this->check = $check;
        $this->criteria = new ArrayCollection($criteria);
    }

    public function edit(Check $check, array $criteria)
    {
        $this->check = $check;
        $this->criteria = new ArrayCollection($criteria);
    }

    public function getId(): AssessmentId
    {
        return $this->id;
    }

    public function getReviewer(): ReviewerId
    {
        return $this->reviewerId;
    }

    public function assignReviewer(ReviewerId $reviewer)
    {
        $this->reviewerId = $reviewer;
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
     * @throws Exceptions\NotExistingSelectedOptionException
     */
    public function getScoredPoints(): float
    {
        return array_reduce($this->criteria->toArray(), function ($prev, Criterion $criterion) {
            return $prev + $criterion->getSelectedValue();
        }, 0);
    }

    public function getTotalPoints(): float
    {
        return array_reduce($this->criteria->toArray(), function ($carry, Criterion $criterion) {
            return $carry + $criterion->getMaxPoint();
        }, 0);
    }
}
