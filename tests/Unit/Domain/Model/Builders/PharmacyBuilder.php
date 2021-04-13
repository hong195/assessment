<?php


namespace Tests\Unit\Domain\Model\Builders;


use Domain\Model\Pharmacy\Email;
use Domain\Model\Pharmacy\Pharmacy;

class PharmacyBuilder
{
    private $id;
    private string $email;
    /**
     * @var PharmacyNumber
     */
    private PharmacyNumber $number;

    public function __construct()
    {
        $this->id = PharmacyId::next();
        $this->email = new Email('pharmacy@mail.com');
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
