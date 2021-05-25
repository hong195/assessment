<?php


namespace Domain\Model\User;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class UserIdType extends GuidType
{
    const USER_ID = 'user_id';

    public function getName()
    {
        return static::USER_ID;
    }

    protected function getValueObjectClassName(): string
    {
        return UserId::class;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new UserId($value) : null;
    }
}
