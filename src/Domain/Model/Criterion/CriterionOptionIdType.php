<?php


namespace Domain\Model\Criterion;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class CriterionOptionIdType extends GuidType
{
    const CRITERION_OPTION_ID = 'option_id';

    public function getName()
    {
        return self::CRITERION_OPTION_ID;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (string) $value;
    }

    protected function getValueObjectClassName(): string
    {
        return OptionId::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new OptionId($value) : null;
    }
}
