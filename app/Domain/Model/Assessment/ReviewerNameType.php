<?php


namespace App\Domain\Model\Assessment;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class ReviewerNameType extends StringType
{
    const REVIEWER_NAME = 'reviewer_name';

    public function getName(): string
    {
        return self::REVIEWER_NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return ReviewerName::class;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ReviewerNameType
    {
        if (empty($name)) {
            return null;
        }

        $name = explode($value, ' ');

        return !empty($value) ? new ReviewerName($name[0], $name[1],$name[2] || null) : null;
    }
}
