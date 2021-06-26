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
        $pharmacistRole = new Role(Role::PHARMACIST);

        $this->assertTrue($reviewerRole->isEqualsTo(Role::REVIEWER));
        $this->assertFalse($reviewerRole->isEqualsTo(Role::ADMIN));
        $this->assertFalse($reviewerRole->isEqualsTo(Role::PHARMACIST));


        $this->assertTrue($adminRole->isEqualsTo(Role::ADMIN));
        $this->assertFalse($adminRole->isEqualsTo(Role::PHARMACIST));
        $this->assertFalse($adminRole->isEqualsTo(Role::REVIEWER));


        $this->assertTrue($pharmacistRole->isEqualsTo(Role::PHARMACIST));
        $this->assertFalse($pharmacistRole->isEqualsTo(Role::ADMIN));
        $this->assertFalse($pharmacistRole->isEqualsTo(Role::REVIEWER));
    }
}
