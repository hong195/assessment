<?php


namespace Domain\Check\ValueObjects;


use Carbon\Carbon;

class CreationDate
{
    /**
     * @var Carbon
     */
    private $creationDate;

    public function __construct(Carbon $creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function __toString(): string
    {
        return  $this->creationDate->format('Y-m-d');
    }
}
