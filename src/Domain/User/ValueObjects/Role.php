<?php


namespace Domain\User\ValueObjects;


use Domain\User\Exception\InvalidRoleException;

class Role
{
    public const REVIEWER = 'reviewer';
    public const SUBSCRIBER = 'subscriber';

    private $role;

    /**
     * Role constructor.
     * @param string $role
     * @throws InvalidRoleException
     */
    public function __construct(string $role)
    {
        $this->validateRole($role);

        $this->role = $role;
    }

    public function __toString(): string
    {
        return $this->role;
    }

    public function isEqualsTo(string $role) : bool
    {
        return $this->role === $role;
    }

    /**
     * @param string $role
     * @throws InvalidRoleException
     */
    private function validateRole(string $role) : void
    {
        if (!in_array($role, [self::REVIEWER, self::SUBSCRIBER])) {
            throw new InvalidRoleException;
        }
    }
}
