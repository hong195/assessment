<?php


namespace App\Domain\Model\Employee;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class Gender
 * @ORM\Embeddable
 */
final class Name
{
    /**
     * @ORM\Column (type="string")
     */
    private string $firstName;
    /**
     * @ORM\Column (type="string", nullable=true)
     */
    private ?string $middle;
    /**
     * @ORM\Column (type="string")
     */
    private string $lastName;

    public function __construct(string $name, string $lastName, string $middle = null)
    {
        $this->firstName = $name;
        $this->middle = $middle;
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function firstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function middle(): string
    {
        return $this->middle;
    }

    /**
     * @return string|null
     */
    public function lastName(): ?string
    {
        return $this->lastName;
    }

    public function __toString(): string
    {
        return "{$this->firstName} {$this->lastName} {$this->middle}";
    }
}
