<?php


namespace Domain\Model\Pharmacy;


use Domain\Model\Pharmacy\Exceptions\InvalidPharmacyEmailException;

final class Email
{
    private string $email;

    /**
     * Email constructor.
     * @param string $email
     * @throws InvalidPharmacyEmailException
     */
    public function __construct(string $email)
    {
        $this->assertValidEmail($email);
        $this->email = $email;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @throws InvalidPharmacyEmailException
     */
    public function assertValidEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidPharmacyEmailException;
        }
    }
}
