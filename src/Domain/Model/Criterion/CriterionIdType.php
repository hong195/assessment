<?php


namespace Domain\Model\Criterion;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class CriterionIdType extends GuidType
{
    const CRITERION_ID = 'criterion_id';

    public function getName()
    {
        return static::CRITERION_ID;
    }

    protected function getValueObjectClassName(): string
    {
        return CriterionId::class;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new CriterionId($value) : null;
    }
}
