<?php


namespace App\Domain\Model\FinalGrade;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use App\Domain\Shared\Id;
use App\Domain\Model\Criterion\OptionId;

class FinalGradeIdType extends GuidType
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
        return FinalGradeId::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new FinalGradeId($value) : null;
    }
}
