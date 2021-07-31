<?php


namespace App\Domain\Model\Assessment;


use App\Domain\Shared\Id;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class ReviewerIdType extends StringType
{
    const REVIEWER_ID = 'reviewer_id';

    public function getName()
    {
        return self::REVIEWER_ID;
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
