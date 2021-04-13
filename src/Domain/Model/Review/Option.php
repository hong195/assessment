<?php


namespace Domain\Model\Review;


final class Option
{
    private string $name;
    /**
     * @var float|int
     */
    private $value;

    public function __construct(string $name, float $value = 0)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return float|int
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
