<?php

namespace App\Domain\Model\SaleManager;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Model\Pharmacy\Pharmacy;
use Doctrine\Common\Collections\Collection;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity
 */
class SaleManager
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Domain\Model\Pharmacy\Pharmacy", inversedBy="saleManagers")
     * @ORM\JoinTable(name="sale_managers_pharmacies")
     */
    private Collection $pharmacies;

    #[Pure] public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->pharmacies = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getSalaManagerId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function changeName(string $name) : void
    {
        $this->name = $name;
    }

    /**
     * @param Collection $pharmacies
     */
    public function assignPharmacis(Collection $pharmacies)
    {
        $this->pharmacies = $pharmacies;
    }

    /**
     * @param Pharmacy $pharmacyToDeAssign
     */
    public function deAssginPharmacy(Pharmacy $pharmacyToDeAssign)
    {
        foreach ($this->pharmacies as $key => $pharmacy) {
            if ($pharmacy->getId()->isEquals($pharmacyToDeAssign->getId())) {
                unset($this->pharmacies[$key]);
                break;
            }
        }
    }
    /**
     * @return ArrayCollection|Collection
     */
    public function getPharmacies(): ArrayCollection|Collection
    {
        return $this->pharmacies;
    }
}
