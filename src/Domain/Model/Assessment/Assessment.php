<?php


namespace Domain\Model\Assessment;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\Column (type="assessment_reviewer_id")
     */
    private ReviewerId $reviewerId;

    /**
     * @ORM\Embedded (class="Check")
     */
    private Check $check;

    /**
     * @ORM\Column (type="json_array")
     */
    private ArrayCollection $criteria;

    /**
     * assessment constructor.
     * @param AssessmentId $id
     * @param Check $check
     * @param Criterion[] $criteria
     */
    public function __construct(AssessmentId $id,
                                Check $check,
                                array $criteria)
    {
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
        },0);
    }
}
