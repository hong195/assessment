<?php


namespace Tests\Builders;


use App\Domain\Model\Pharmacy\Email;
use App\Domain\Model\Pharmacy\Pharmacy;
use App\Domain\Model\Pharmacy\PharmacyId;
use App\Domain\Model\Pharmacy\PharmacyNumber;

class PharmacyBuilder
{
    private PharmacyId $id;

    private Email $email;

    private PharmacyNumber $number;

    public function __construct()
    {
        $this->id = PharmacyId::next();
        $this->email = new Email('pharmacy@mail.com');
        $this->number = new PharmacyNumber('test-number');
    }

    public static function aPharmacy(): PharmacyBuilder
    {
        return new self();
    }

    public function withId(PharmacyId $id): PharmacyBuilder
    {
        $this->id = $id;
        return $this;
    }

    public function withNumber(PharmacyNumber $number): PharmacyBuilder
    {
        $this->number = $number;
        return $this;
    }

    public function withEmail(Email $email): PharmacyBuilder
    {
        $this->email = $email;
        return $this;
    }

    public function build(): Pharmacy
    {
        return new Pharmacy($this->id, $this->number, $this->email);
    }
}
