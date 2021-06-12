<?php


namespace Domain\Model\EfficiencyAnalysis;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Domain\Exceptions\NotFoundEntityException;
use Domain\Model\Assessment\Assessment;
use Domain\Model\Assessment\AssessmentId;
use Domain\Model\Assessment\Check;
use Domain\Model\EfficiencyAnalysis\Exceptions\InvalidRatingMonthException;
use Domain\Model\EfficiencyAnalysis\Exceptions\MaxReviewsForMonthReachedException;
use Domain\Model\EfficiencyAnalysis\Exceptions\ModificationRatingException;
use Domain\Model\Employee\EmployeeId;

/**
 * Class EfficiencyAnalysis
 * @ORM\Entity
 */
class EfficiencyAnalysis
{
    const ALLOWED_REVIEWS_AMOUNT = 10;
    /**
     * @ORM\Id
     * @ORM\Column (type="efficiency_analysis_id")
     */
    private EfficiencyAnalysisId $id;
    /**
     * @ORM\OneToMany(targetEntity="Domain\Model\Assessment\Assessment", mappedBy="analysis", cascade={"persist", "remove"})
     */
    private Collection  $assessments;
    /**
     * @ORM\Embedded (class="Month")
     */
    private Month $month;
    /**
     * @ORM\Column  (type="float", nullable=true)
     */
    private ?float $scored = null;
    /**
     * @ORM\Column  (type="float", nullable=true)
     */
    private ?float $total = null;
    /**
     * @ORM\Column  (type="employee_id")
     */
    private EmployeeId $employeeId;
    /**
     * @ORM\Embedded (class="Status")
     */
    private Status $status;


    public function __construct(EfficiencyAnalysisId $analysisId, EmployeeId $employee, Month $date)
    {
        $this->assessments = new ArrayCollection([]);
        $this->month = $date;
        $this->employeeId = $employee;
        $this->status = new Status(Status::UNCOMPLETED);
        $this->id = $analysisId;
    }

    /**
     * @param AssessmentId $assessmentId
     * @param Check $check
     * @param array $criteria
     * @return Assessment
     * @throws InvalidRatingMonthException
     * @throws MaxReviewsForMonthReachedException
     */
    public function addReview(AssessmentId $assessmentId,
                              Check $check,
                              array $criteria): Assessment
    {
        if ($this->isCompleted()) {
            throw new MaxReviewsForMonthReachedException();
        }

        if (!$this->month->isDateBetween($check->getServiceDate())) {
            throw new InvalidRatingMonthException('Check service date must be between assessment date');
        }

        $assessment = new Assessment($assessmentId, $this, $check, $criteria);
        $this->assessments->add($assessment);

        if ($this->isMaxReviewsAdded()) {
            $this->generateScore();
            $this->status = new Status(Status::COMPLETED);
        }

        return $assessment;
    }

    /**
     * @param AssessmentId $reviewId
     * @throws ModificationRatingException|NotFoundEntityException
     */
    public function removeReview(AssessmentId $reviewId)
    {
        if ($this->isCompleted()) {
            throw new ModificationRatingException('Cannot remove review from completed assessment');
        }

        foreach ($this->assessments as $k => $assessment) {
            if ($reviewId->isEqual($assessment->getId())) {
                unset($this->assessments[$k]);
                break;
            }
        }
    }

    /**
     * @param AssessmentId $reviewId
     * @param Check $check
     * @param array $criteria
     * @throws ModificationRatingException
     */
    public function editReview(AssessmentId $reviewId, Check $check, array $criteria)
    {
        if ($this->isCompleted()) {
            throw new ModificationRatingException('Cannot update review from completed assessment');
        }

        foreach ($this->assessments as $review) {
            if ($reviewId->isEqual($review->getId())) {
                $review->edit($check, $criteria);
                break;
            }
        }
    }

    public function isMaxReviewsAdded(): bool
    {
        return self::ALLOWED_REVIEWS_AMOUNT === $this->getAssessmentsCount();
    }

    public function isCompleted(): bool
    {
        return  $this->isMaxReviewsAdded() && $this->status->isEqualTo(Status::COMPLETED);
    }

    /**
     * @throws \Domain\Model\Assessment\Exceptions\NotExistingSelectedOptionException
     */
    private function generateScore(): void
    {
        $this->scored = array_reduce($this->assessments->toArray(),
            fn($carry, Assessment $item) => $carry + $item->getScoredPoints(), 0);

        $this->total = array_reduce($this->assessments->toArray(),
            fn($carry, Assessment $item) => $carry + $item->getTotalPoints(),
            0);
    }

    public function getId(): EfficiencyAnalysisId
    {
        return $this->id;
    }

    public function getEmployeeId(): EmployeeId
    {
        return $this->employeeId;
    }

    public function getScored(): ?float
    {
        return $this->scored;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function getAssessmentsCount(): int
    {
        return $this->getAssessments()->count();
    }

    public function getAssessments(): Collection
    {
        return $this->assessments;
    }

    public function getMonth(): Month
    {
        return $this->month;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}
