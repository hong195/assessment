<?php


namespace Domain\Model\Employee;


final class Employee extends AbstractParticipant
{
    public function __construct($identity, string $name)
    {
        parent::__construct($identity, $name);
    }
}
