<?php


namespace Domain\Check\ValueObjects;


use Domain\Check\Entities\Attribute;

class AttributeValue
{
    /**
     * @var Attribute
     */
    private $attribute;
    private $value;

    public function __construct(Attribute $attribute, $value)
    {
        $this->attribute = $attribute;
        $this->value = $value;
    }
}
