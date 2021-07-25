<?php


namespace App\DataTransferObjects;


class UserDto
{
    public string $name;

    public ?string $password;
    private string $last_name;
    private string $middle_name;
    private string $login;
    private string $role;

    public function __construct(string $name,
                                string $last_name,
                                string $middle_name,
                                string $login,
                                string $role,
                                string $password = null)
    {
        $this->name = $name;
        $this->password = $password;
        $this->last_name = $last_name;
        $this->middle_name = $middle_name;
        $this->login = $login;
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ?string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getMiddleName(): string
    {
        return $this->middle_name;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }
}
