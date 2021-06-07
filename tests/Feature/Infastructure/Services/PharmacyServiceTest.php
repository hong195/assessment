<?php


namespace Tests\Feature\Infastructure\Services;


use Doctrine\ORM\EntityManagerInterface;
use Domain\Exceptions\NotFoundEntityException;
use Domain\Model\Pharmacy\Email;
use Domain\Model\Pharmacy\PharmacyId;
use Domain\Model\Pharmacy\PharmacyNumber;
use Domain\Model\Pharmacy\PharmacyRepository;
use Infastructure\Exceptions\PharmacyNumberHasBeenAlreadyTakenException;
use Infastructure\Services\PharmacyService;
use Tests\Feature\DoctrineMigrationsTrait;
use Tests\TestCase;


/**
 * @group integration
 */
class PharmacyServiceTest extends TestCase
{
    use DoctrineMigrationsTrait;

    private PharmacyRepository $repository;

    private PharmacyService $service;

    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resetMigrations();
        $this->repository = app()->make(PharmacyRepository::class);
        $this->em = app()->make(EntityManagerInterface::class);
        $this->service = app()->make(PharmacyService::class);
    }

    public function test_can_add_pharmacy()
    {
        $aPharmacyId = PharmacyId::next();
        $aPharmacyNumber = new PharmacyNumber('#1');
        $aPharmacyEmail = new Email('pharmacy@gmail.com');

        $this->service->addPharmacy($aPharmacyId, $aPharmacyNumber, $aPharmacyEmail);
        $addedPharmacy = $this->repository->findById($aPharmacyId);

        $this->assertDatabaseCount('pharmacies',1);
        $this->assertTrue($addedPharmacy->getId()->isEqual($aPharmacyId));
    }

    public function test_expects_exception_when_pharmacy_number_is_not_unique()
    {
        $aPharmacyId = PharmacyId::next();
        $aPharmacyNumber = new PharmacyNumber('#1');
        $aPharmacyEmail = new Email('pharmacy@gmail.com');

        $this->service->addPharmacy($aPharmacyId, $aPharmacyNumber, $aPharmacyEmail);

        $this->expectException(PharmacyNumberHasBeenAlreadyTakenException::class);

        $this->service->addPharmacy($aPharmacyId, $aPharmacyNumber, $aPharmacyEmail);
    }

    public function test_can_update()
    {
        $aPharmacyId = PharmacyId::next();
        $aPharmacyNumber = new PharmacyNumber('#1');
        $aPharmacyEmail = new Email('pharmacy@gmail.com');

        $this->service->addPharmacy($aPharmacyId, $aPharmacyNumber, $aPharmacyEmail);
        $this->service->updatePharmacy($aPharmacyId, $aPharmacyNumber, $aPharmacyEmail);
        $updatedPharmacy = $this->repository->findById($aPharmacyId);

        $this->assertEquals((string) $aPharmacyNumber, (string) $updatedPharmacy->getNumber());
        $this->assertEquals((string) $aPharmacyEmail, (string) $updatedPharmacy->getEmail());
    }

    public function test_can_delete_pharmacy()
    {
        $aPharmacyId = PharmacyId::next();
        $aPharmacyNumber = new PharmacyNumber('#1');
        $aPharmacyEmail = new Email('pharmacy@gmail.com');
        $this->service->addPharmacy($aPharmacyId, $aPharmacyNumber, $aPharmacyEmail);
        $this->repository->findById($aPharmacyId);

        $this->service->deletePharmacy($aPharmacyId);

        $this->assertDatabaseCount('pharmacies', 0);
    }

    public function test_cant_delete_not_existing_pharmacy()
    {
        $aPharmacyId = PharmacyId::next();

        $this->expectException(NotFoundEntityException::class);

        $this->service->deletePharmacy($aPharmacyId);
    }
}
