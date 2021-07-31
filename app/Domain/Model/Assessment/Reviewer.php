<?php


namespace App\Domain\Model\Assessment;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Reviewer
 * @ORM\Embeddable
 */
class Reviewer
{
    /**
     * @ORM\Column (type="reviewer_id", nullable=true)
     */
    private ReviewerId $reviewerId;
    /**
     * @ORM\Column (type="reviewer_name", nullable=true)
     */
    private ReviewerName $name;

    public function __construct(ReviewerId $reviewerId, ReviewerName $name)
    {
        $this->reviewerId = $reviewerId;
        $this->name = $name;
    }

    /**
     * @return ReviewerId
     */
    public function getReviewerId(): ReviewerId
    {
        return $this->reviewerId;
    }

    /**
     * @return ReviewerName
     */
    public function getName(): ReviewerName
    {
        return $this->name;
    }
}
