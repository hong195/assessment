<?php


namespace Tests\Unit\Domain\Model\Pharmacy;


use Domain\Model\Pharmacy\Email;
use Domain\Model\Pharmacy\PharmacyNumber;
use Domain\Model\User\FullName;
use Domain\Model\User\Login;
use Domain\Model\User\UserId;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Domain\Model\Builders\PharmacyBuilder;

class PharmacyTest extends TestCase
{
    public function test_can_add_a_user()
    {
        $userId = new UserId(UserId::next());
        $login = new Login('user-login');
        $fullName = new FullName('Aname', 'aLastName', 'Apatronymic');
        $pharmacy = PharmacyBuilder::aPharmacy()->build();

        $user = $pharmacy->addUser($userId, $login, $fullName);

        $this->assertCount(1, $pharmacy->getUsers());
        $this->assertContains($user, $pharmacy->getUsers());
    }

    public function test_get_all_users()
    {
        $pharmacy = PharmacyBuilder::aPharmacy()->build();

        $userIdA = new UserId(UserId::next());
        $loginA = new Login('user-login');
        $fullNameA = new FullName('Aname', 'aLastName', 'Apatronymic');

        $pharmacy->addUser($userIdA, $loginA, $fullNameA);

        $userIdB = new UserId(UserId::next());
        $loginB = new Login('user-login');
        $fullNameB = new FullName('Aname', 'aLastName', 'Apatronymic');

        $pharmacy->addUser($userIdB, $loginB, $fullNameB);

        $this->assertCount(2, $pharmacy->getUsers());
    }

    public function test_can_change_email()
    {
        $pharmacy = PharmacyBuilder::aPharmacy()->withEmail(new Email('old-email@gmail.com'))->build();
        $newEmail = new Email('new-email@gmail.com');

        $pharmacy->changeEmail($newEmail);

        $this->assertEquals($newEmail, $pharmacy->getEmail());
    }

    public function test_can_change_number()
    {
        $pharmacy = PharmacyBuilder::aPharmacy()->withNumber(new PharmacyNumber('old-number'))->build();
        $newNumber = new PharmacyNumber('new-number');

        $pharmacy->changeNumber($newNumber);

        $this->assertEquals($newNumber, $pharmacy->getNumber());
    }
}
