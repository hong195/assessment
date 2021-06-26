<?php


namespace Tests\Unit\Domain\Model\Pharmacy;


use App\Domain\Model\Pharmacy\Email;
use App\Exceptions\InvalidPharmacyEmailException;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function test_email_must_be_valid()
    {
        $this->expectException(InvalidPharmacyEmailException::class);

        new Email('invalid-email');
    }
}
