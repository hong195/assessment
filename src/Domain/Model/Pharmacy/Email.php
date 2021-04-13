<?php


namespace Domain\Model\Pharmacy;


class Email
{
    /**
     * @var string
     */
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
