<?php


namespace App\Domain\Model\Assessment;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class AssessmentReviewerIdType extends GuidType
{
    const ASSESSMENT_REVIEWER_ID = 'assessment_reviewer_id';

    public function getName()
    {
        return self::ASSESSMENT_REVIEWER_ID;
    }

    protected function getValueObjectClassName(): string
    {
        return ReviewerId::class;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (string)$value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new ReviewerId($value) : null;
    }
}
