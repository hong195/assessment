<?php


namespace Domain\Model\EfficiencyAnalysis;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Domain\Id;
use Domain\Model\Criterion\OptionId;

class EAIdType extends GuidType
{
    const EA_ID = 'efficiency_analysisId';

    public function getName()
    {
        return self::EA_ID;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (string) $value;
    }

    protected function getValueObjectClassName(): string
    {
        return EfficiencyAnalysisId::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new EfficiencyAnalysisId($value) : null;
    }
}
