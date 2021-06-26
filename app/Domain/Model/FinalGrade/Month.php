<?php


namespace App\Domain\Model\FinalGrade;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use App\Exceptions\InvalidRatingMonthException;

/**
 * Class Month
 * @ORM\Embeddable
 */
final class Month
{
    const MIN_YEAR = 2020;

    /**
     * @ORM\Column (type="datetime")
     */
    private \DateTime $date;

    /**
     * @throws InvalidRatingMonthException
     */
    public function __construct(\DateTime $date)
    {
        $this->assertValidDate($date->format('Y'), $date->format('m'));
        $this->date = $date;
    }

    public function isEqual(Month $month) : bool
    {
        return $this->getMonth() === $month->getMonth() && $this->getYear() === $month->getYear();
    }

    public function __toString(): string
    {
       return $this->date->format('Y-m-d');
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getMonth(): int
    {
        return (int) $this->date->format('m');
    }

    public function getYear(): int
    {
        return (int) $this->date->format('Y');
    }

    /**
     * @param DateTime $aDate
     * @return bool
     * @throws InvalidRatingMonthException
     */
    public function isDateBetween(\DateTime $aDate) : bool
    {
        $this->assertValidDate(date('Y', $aDate->getTimestamp()), date('m', $aDate->getTimestamp()));

        $aDate = date('Y-m-d', $aDate->getTimestamp());
        $date = $this->date->format('Y-m-d');
        $firstDatOfMonth = date('Y-m-01', strtotime($date));
        $lastDatOfMonth = date('Y-m-t', strtotime($date));

        return $aDate >= $firstDatOfMonth && $aDate <= $lastDatOfMonth;
    }
    /**
     * @param int $year
     * @param int $month
     * @throws InvalidRatingMonthException
     */
    private function assertValidDate(int $year, int $month)
    {
        if ($year < self::MIN_YEAR) {
            throw new InvalidRatingMonthException();
        }
        try {
            $date = $year . '-'  . $month;
            $date = new \DateTime($date);
        } catch (\Exception $e) {
            throw new InvalidRatingMonthException();
        }
        $now = new \DateTime('now');

        if ($date > $now) {
            throw new InvalidRatingMonthException('Assessment date must be less than today');
        }
    }
}
