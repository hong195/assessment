<?php


namespace Tests\Unit\Domain\Model\Employee;

use App\Domain\Model\Employee\Exceptions\InvalidGenderValueException;
use App\Domain\Model\Employee\Gender;
use PHPUnit\Framework\TestCase;

class GenderTest extends TestCase
{
    public function test_gender_can_be_male()
    {
        $gender = new Gender('male');
        $this->assertEquals(Gender::MALE, $gender->getValue());
    }

    public function test_gender_can_be_female()
    {
        $gender = new Gender('female');
        $this->assertEquals(Gender::FEMALE, $gender->getValue());
    }

    public function test_expects_exception_when_gender_value_is_invalid()
    {
        $this->expectException(InvalidGenderValueException::class);
        new Gender('invalid-gender-value');
    }
}
