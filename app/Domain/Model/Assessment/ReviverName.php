<?php


namespace App\Domain\Model\Assessment;



final class ReviverName
{
    private string $firstName;

    private ?string $middle;

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
