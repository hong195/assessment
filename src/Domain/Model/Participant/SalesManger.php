<?php


namespace Domain\Model\Participant;


use Doctrine\Common\Collections\ArrayCollection;

final class SalesManger extends AbstractParticipant
{
    private ArrayCollection $pharmacies;

    public function __construct($identity, Name $name)
    {
        parent::__construct($identity, $name);
        $this->pharmacies = new ArrayCollection([]);
    }

    public function getPharmacies(): ArrayCollection
    {
        return $this->pharmacies;
    }
}
