<?php


namespace Domain\Model\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class FullName
{
    /**
     * @ORM\Column (type="string")
     */
    private string $first;
    /**
     * @ORM\Column (type="string")
     */
    private ?string $middle;
    /**
     * @ORM\Column (type="string")
     */
    private string $last;

    public function __construct(string $name, string $lastName, string $patronymic = null)
    {
        $this->first = $name;
        $this->middle = $patronymic;
        $this->last = $lastName;
    }

    /**
     * @return string
     */
    public function firstName(): string
    {
        return $this->first;
    }

    /**
     * @return string
     */
    public function patronymic(): string
    {
        return $this->middle;
    }

    /**
     * @return string|null
     */
    public function lastName(): ?string
    {
        return $this->last;
    }

    public function __toString(): string
    {
        return "{$this->first} {$this->last} {$this->middle}";
    }
}
