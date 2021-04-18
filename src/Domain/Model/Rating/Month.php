<?php


namespace Domain\Model\Rating;


use Domain\Model\Rating\Exceptions\InvalidRatingMonthException;

final class Month
{
    private \DateTimeImmutable $date;
    /**
     * Date constructor.
     * @param int $year
     * @param int $month
     * @throws InvalidRatingMonthException
     */
    public function __construct(int $year, int $month)
    {
        $this->assertValidDate($year, $month);
        $this->date = (new \DateTimeImmutable())->setDate($year, $month, 1);
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
     * @param string $aDate
     * @return bool
     * @throws InvalidRatingMonthException
     */
    public function isDateBetween(string $aDate) : bool
    {
        $this->assertValidDate(date('Y', strtotime($aDate)), date('m', strtotime($aDate)));

        $aDate = date('Y-m-d', strtotime($aDate));
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
