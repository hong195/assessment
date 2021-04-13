<?php


namespace Domain\Model\User;


class FullName
{
    private string $firstName;

    private ?string $patronymic;

    private string $lastName;

    public function __construct(string $name, string $lastName, string $patronymic = null)
    {
        $this->firstName = $name;
        $this->patronymic = $patronymic;
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
    public function patronymic(): string
    {
        return $this->patronymic;
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
        return "{$this->firstName} {$this->lastName} {$this->patronymic}";
    }
}
