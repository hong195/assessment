<?php


namespace Domain\Model\User;


use Domain\Model\User\Exceptions\InvalidRoleException;

final class Role
{
    public const REVIEWER = '1';
    public const PHARMACIST = '2';
    public const ADMIN = '3';

    private string $role;

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
        if (!in_array($role, [self::REVIEWER, self::PHARMACIST, self::ADMIN])) {
            throw new InvalidRoleException;
        }
    }
}
