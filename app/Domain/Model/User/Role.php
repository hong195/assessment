<?php


namespace App\Domain\Model\User;
use Doctrine\ORM\Mapping as ORM;

use App\Exceptions\InvalidRoleException;

/**
 * @ORM\Embeddable
 */
final class Role
{
    public const REVIEWER = 'reviewer';
    public const EDITOR = 'editor';
    public const ADMIN = 'admin';
    public const SALE_MANAGER = 'sale_manager';

    /**
     * @ORM\Column(type="string", name="role")
     */
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
        if (!in_array($role, [self::REVIEWER, self::EDITOR, self::ADMIN, self::SALE_MANAGER])) {
            throw new InvalidRoleException;
        }
    }
}
