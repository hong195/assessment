<?php


namespace Domain\Model\Criterion;


use Domain\Model\Criterion\Exceptions\CriterionException;

final class Option
{
    private string $name;

    private float $value;

    private OptionId $optionId;

    /**
     * Option constructor.
     * @param OptionId $optionId
     * @param string $name
     * @param float|int $value
     * @throws CriterionException
     */
    public function __construct(OptionId $optionId, string $name, float $value = 0)
    {
        $this->assertNotEmpty($name);
        $this->assertValueNotNegative($value);
        $this->optionId = $optionId;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @param string $name
     * @throws CriterionException
     */
    private function assertNotEmpty(string $name)
    {
        if (empty($name)) {
            throw new CriterionException('Option name cannot be empty!');
        }
    }

    /**
     * @param float $value
     * @throws CriterionException
     */
    private function assertValueNotNegative(float $value)
    {
        if ($value < 0) {
            throw new CriterionException('Option value cannot be negative!');
        }
    }

    public function isNameEquals(string $name) : bool
    {
        return $name === $this->name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getOptionId(): OptionId
    {
        return $this->optionId;
    }
}
