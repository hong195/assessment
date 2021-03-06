<?php


namespace App\Domain\Model\FinalGrade;


use App\Exceptions\UnknownFinalGradeStatusException;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Status
 * @ORM\Embeddable
 */
final class Status
{
    const UNCOMPLETED = 'uncompleted';
    const COMPLETED = 'completed';

    /**
     * @ORM\Column (type="string")
     */
    private string $status;

    /**
     * Status constructor.
     * @param string $status
     * @throws UnknownFinalGradeStatusException
     */
    public function __construct(string $status)
    {
        $this->assertValid($status);
        $this->status = $status;
    }


    public function isEqualTo(string $anotherStatus): bool
    {
        return $this->status === $anotherStatus;
    }

    public function __toString(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @throws UnknownFinalGradeStatusException
     */
    private function assertValid(string $status)
    {
        if (!in_array($status, [self::UNCOMPLETED, self::COMPLETED])) {
            throw new UnknownFinalGradeStatusException;
        }
    }
}
