<?php


namespace Domain\Check\ValueObjects;


class AttributeId
{
    private $attributeId;

    public function __construct($attributeId)
    {
        $this->attributeId = $attributeId;
    }

    public function __toString(): string
    {
        return (string) $this->attributeId;
    }
}
