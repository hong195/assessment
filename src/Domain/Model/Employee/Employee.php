<?php


namespace Domain\Model\Employee;


class Employee extends AbstractParticipant
{
    public function __construct($identity, string $name)
    {
        parent::__construct($identity, $name);
    }
}
