<?php


namespace Tests\Unit\Domain\Model\User;


use App\Exceptions\InvalidRoleException;
use App\Domain\Model\User\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function test_expect_exception_when_create_non_existing_role()
    {
        $this->expectException(InvalidRoleException::class);
        new Role('non-existing-role');
    }

    public function test_can_create_different_user_roles()
    {
        $reviewerRole = new Role(Role::REVIEWER);
        $adminRole = new Role(Role::ADMIN);
        $editorRole = new Role(Role::EDITOR);

        $this->assertTrue($reviewerRole->isEqualsTo(Role::REVIEWER));
        $this->assertFalse($reviewerRole->isEqualsTo(Role::ADMIN));
        $this->assertFalse($reviewerRole->isEqualsTo(Role::EDITOR));


        $this->assertTrue($adminRole->isEqualsTo(Role::ADMIN));
        $this->assertFalse($adminRole->isEqualsTo(Role::EDITOR));
        $this->assertFalse($adminRole->isEqualsTo(Role::REVIEWER));


        $this->assertTrue($editorRole->isEqualsTo(Role::EDITOR));
        $this->assertFalse($editorRole->isEqualsTo(Role::ADMIN));
        $this->assertFalse($editorRole->isEqualsTo(Role::REVIEWER));
    }
}
