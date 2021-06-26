<?php


namespace App\Domain\Model\Employee;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class EmployeeIdType extends GuidType
{
    const EMPLOYEE_ID = 'employee_id';

    public function getName()
    {
        return self::EMPLOYEE_ID;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (string) $value;
    }

    protected function getValueObjectClassName(): string
    {
        return EmployeeId::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new EmployeeId($value) : null;
    }
}
