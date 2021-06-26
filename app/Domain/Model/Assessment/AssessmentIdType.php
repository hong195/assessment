<?php


namespace App\Domain\Model\Assessment;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class AssessmentIdType extends GuidType
{
    const ASSESSMENT_ID = 'assessment_id';

    public function getName()
    {
        return self::ASSESSMENT_ID;
    }

    protected function getValueObjectClassName(): string
    {
        return AssessmentId::class;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (string)$value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new AssessmentId($value) : null;

    }
}
