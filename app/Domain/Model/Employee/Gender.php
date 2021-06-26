<?php


namespace App\Domain\Model\Employee;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Model\Employee\Exceptions\InvalidGenderValueException;

/**
 * Class Gender
 * @ORM\Embeddable
 */
final class Gender
{
    /**
     * @ORM\Column (type="string")
     */
    private string $gender;

    const MALE = 'male';
    const FEMALE = 'female';

    /**
     * Gender constructor.
     * @param string $gender
     * @throws InvalidGenderValueException
     */
    public function __construct(string $gender)
    {
        $this->validateGender($gender);
        $this->gender = $gender;
    }

    /**
     * @param string $gender
     * @throws InvalidGenderValueException
     */
    private function validateGender(string $gender)
    {
        if (!in_array($gender, [self::FEMALE, self::MALE])) {
            throw new InvalidGenderValueException('Invalid gender');
        }
    }

    public function getValue(): string
    {
        return $this->gender;
    }
}
