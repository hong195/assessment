<?php


namespace App\Domain\Model\Assessment;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Model\FinalGrade\FinalGrade;

/**
 * Class Assessment
 * @ORM\Entity
 */
class Assessment
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
    private FinalGrade $analysis;
    /**
     * @ORM\Column (type="assessment_reviewer_id", nullable=true)
     */
    private ?ReviewerId $reviewerId;

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
     * @param FinalGrade $analysis
     * @param Check $check
     * @param array $criteria
     */
    public function __construct(AssessmentId $id,
                                FinalGrade $analysis,
                                Check $check,
                                array $criteria)
    {
        $this->analysis = $analysis;
        $this->id = $id;
        $this->check = $check;
        $this->criteria = new ArrayCollection($criteria);
        $this->reviewerId = null;
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

    public function getReviewer(): ?ReviewerId
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
