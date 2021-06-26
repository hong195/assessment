<?php

namespace Tests\Feature\Infastructure\Persistence\InMemory;

use App\Domain\Model\Pharmacy\Pharmacy;
use App\Domain\Model\Pharmacy\PharmacyNumber;
use App\Domain\Model\Pharmacy\PharmacyRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryPharmacyRepository;
use Tests\TestCase;
use Tests\Builders\PharmacyBuilder;

class PharmacyRepositoryTest extends TestCase
{
    private PharmacyRepository $pharmacyRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pharmacyRepository = new InMemoryPharmacyRepository();
    }

    public function test_create_pharmacy()
    {
        $aPharmacy = PharmacyBuilder::aPharmacy()->build();
        $this->pharmacyRepository->add($aPharmacy);
        $foundPharmacy = $this->pharmacyRepository->findById($aPharmacy->getId());

        $this->assertNotEmpty($this->pharmacyRepository->all());
        $this->assertInstanceOf(Pharmacy::class, $foundPharmacy);
        $this->assertSame($aPharmacy, $foundPharmacy);
    }

    public function test_remove()
    {
        $aPharmacy = PharmacyBuilder::aPharmacy()->build();
        $aPharmacy2 = PharmacyBuilder::aPharmacy()->build();

        $this->pharmacyRepository->add($aPharmacy);
        $this->pharmacyRepository->add($aPharmacy2);
        $this->pharmacyRepository->remove($aPharmacy);

        $this->assertNotContains($aPharmacy, $this->pharmacyRepository->all());
        $this->assertCount(1, $this->pharmacyRepository->all());
    }

    public function test_get_all()
    {
        $aPharmacy = PharmacyBuilder::aPharmacy()->build();
        $aPharmacy2 = PharmacyBuilder::aPharmacy()->build();

        $this->pharmacyRepository->add($aPharmacy);
        $this->pharmacyRepository->add($aPharmacy2);

        $this->assertNotEmpty($this->pharmacyRepository->all());
        $this->assertContains($aPharmacy, $this->pharmacyRepository->all());
        $this->assertContains($aPharmacy2, $this->pharmacyRepository->all());
    }

    public function test_can_find_by_number()
    {
        $aPharmacyNumber = new PharmacyNumber('#1');

        $aPharmacy = PharmacyBuilder::aPharmacy()->withNumber($aPharmacyNumber)->build();
        $this->pharmacyRepository->add($aPharmacy);
        $found = $this->pharmacyRepository->findByNumber($aPharmacyNumber);

        $this->assertContains($aPharmacy, $found);
    }
}
