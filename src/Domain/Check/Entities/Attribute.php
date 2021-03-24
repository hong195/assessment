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

    public function __construct(AttributeId $attributeId, bool $usedInCheckCalculation = false)
    {
        $this->attributeId = $attributeId;
        $this->usedInCheckCalculation = $usedInCheckCalculation;
    }

    public function isUsedInCheckCalculation(): bool
    {
        return $this->usedInCheckCalculation;
    }

    public function addValue()
    {

    }
}
