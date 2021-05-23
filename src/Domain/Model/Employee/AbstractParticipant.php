<?php


namespace Domain\Model\Employee;


abstract class AbstractParticipant
{
    protected string $identity;

    protected Name $name;

    public function __construct(string $identity, Name $name)
    {
        $this->identity = $identity;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }
}
