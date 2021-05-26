<?php


namespace Domain\Model\Pharmacy;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class PharmacyIdType extends GuidType
{
    const PHARMACY_ID = 'pharmacy_id';

    public function getName()
    {
        return static::PHARMACY_ID;
    }

    protected function getValueObjectClassName(): string
    {
        return PharmacyId::class;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new PharmacyId($value) : null;
    }
}
