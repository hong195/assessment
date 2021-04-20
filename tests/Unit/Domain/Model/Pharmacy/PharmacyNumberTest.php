<?php


namespace Tests\Unit\Domain\Model\Pharmacy;


use Domain\Model\Pharmacy\Exceptions\InvalidPharmacyNumberException;
use Domain\Model\Pharmacy\PharmacyNumber;
use PHPUnit\Framework\TestCase;

class PharmacyNumberTest extends TestCase
{
    public function test_email_must_be_valid()
    {
        $this->expectException(InvalidPharmacyNumberException::class);

        new PharmacyNumber('');
    }
}
