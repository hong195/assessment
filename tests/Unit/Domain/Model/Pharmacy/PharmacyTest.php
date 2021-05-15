<?php


namespace Tests\Unit\Domain\Model\Pharmacy;


use Domain\Model\Pharmacy\Email;
use Domain\Model\Pharmacy\PharmacyNumber;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Domain\Model\Builders\PharmacyBuilder;

class PharmacyTest extends TestCase
{
    public function test_can_change_email()
    {
        $pharmacy = PharmacyBuilder::aPharmacy()->withEmail(new Email('old-email@gmail.com'))->build();
        $newEmail = new Email('new-email@gmail.com');

        $pharmacy->changeEmail($newEmail);

        $this->assertEquals($newEmail, $pharmacy->getEmail());
        $this->assertEquals('new-email@gmail.com', (string) $pharmacy->getEmail());
    }

    public function test_can_change_number()
    {
        $pharmacy = PharmacyBuilder::aPharmacy()->withNumber(new PharmacyNumber('old-number'))->build();
        $newNumber = new PharmacyNumber('new-number');

        $pharmacy->changeNumber($newNumber);

        $this->assertEquals($newNumber, $pharmacy->getNumber());
        $this->assertEquals('new-number', (string) $pharmacy->getNumber());
    }
}
