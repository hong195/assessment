<?php


namespace Domain\Model\Assessment;


use Domain\Model\Assessment\Exceptions\InvalidServiceDateException;

final class ServiceDate
{
    private int $year;

    private int $month;

    private int $day;

    /**
     * @var \DateTimeImmutable|false
     */
    private $date;

    /**
     * CreationDate constructor.
     * @param int $year
     * @param int $month
     * @param int $day
     * @throws InvalidServiceDateException
     */
    public function __construct(int $year, int $month, int $day)
    {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;

        $this->validateDate($year, $month, $day);
        $this->date = (new \DateTimeImmutable)->setDate($year, $month, $day);
    }

    public function __toString(): string
    {
        return $this->date->format('Y-m-d');
    }

    public function getDate(): string
    {
        return $this->date->format('Y-m-d');
    }

    public function year(): int
    {
        return (int)$this->date->format('Y');
    }

    public function month(): int
    {
        return (int)$this->date->format('m');
    }

    public function day(): int
    {
        return (int)$this->date->format('d');
    }

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     * @throws InvalidServiceDateException
     */
    private function validateDate(int $year, int $month, int $day)
    {
        $now = new \DateTime('now');
        $creationDate = new \DateTime($year . '-' . $month . '-' . $day);

        if ($creationDate > $now) {
            throw new InvalidServiceDateException('Creation date must be less than today');
        }
    }
}
