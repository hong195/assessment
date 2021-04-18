<?php


namespace Tests\Unit\Domain\Model\Pharmacy;


use Domain\Model\Pharmacy\Email;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Domain\Model\Builders\PharmacyBuilder;

class PharmacyTest extends TestCase
{
    public function test_can_create_a_pharmacy()
    {
        $this->assertTrue(true);
//        $email = new Email('test@gmail.com');
//        $pharmacy = PharmacyBuilder::aPharmacy()->withEmail($email)->withNumber()->build();
    }
}
