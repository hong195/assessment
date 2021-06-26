<?php


namespace Tests\Unit\Domain\Model\Pharmacy;


use App\Exceptions\InvalidPharmacyNumberException;
use App\Domain\Model\Pharmacy\PharmacyNumber;
use PHPUnit\Framework\TestCase;

class PharmacyNumberTest extends TestCase
{
    public function test_email_must_not_be_empty()
    {
        $this->expectException(InvalidPharmacyNumberException::class);

        new PharmacyNumber('');
    }
}
