<?php


namespace Domain\Check\Entities;


use Domain\Check\ValueObjects\AttributeId;

class Attribute
{
    /**
     * @var string
     */
    private $attributeId;
    /**
     * @var bool
     */
    private $usedInCheckCalculation;

    private $value;

    public function __construct(AttributeId $attributeId, $value, $description = '')
    {
        $this->attributeId = $attributeId;
        $this->value = $value;
    }

    public function getValue(): float
    {
        return floatval($this->value);
    }
}
