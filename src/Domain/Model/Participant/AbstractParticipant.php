<?php


namespace Domain\Model\Participant;


abstract class AbstractParticipant
{
    private $identity;
    private Name $name;

    public function __construct($identity, Name $name)
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
