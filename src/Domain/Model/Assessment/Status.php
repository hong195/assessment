<?php


namespace Domain\Model\Assessment;


use Domain\Model\Assessment\Exceptions\UnknownAssessmentStatusException;

final class Status
{
    const UNCOMPLETED = 'uncompleted';
    const COMPLETED = 'completed';

    private string $status;

    /**
     * Status constructor.
     * @param string $status
     * @throws UnknownAssessmentStatusException
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
     * @throws UnknownAssessmentStatusException
     */
    private function assertValid(string $status)
    {
        if (!in_array($status, [self::UNCOMPLETED, self::COMPLETED])) {
            throw new UnknownAssessmentStatusException;
        }
    }
}
