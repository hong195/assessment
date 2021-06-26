<?php


namespace Tests\Feature\Infastructure\Services;


use App\Http\DataTransferObjects\PharmacyDto;
use Doctrine\ORM\EntityManagerInterface;
use App\Exceptions\NotFoundEntityException;
use App\Domain\Model\Pharmacy\Pharmacy;
use App\Domain\Model\Pharmacy\PharmacyNumber;
use App\Domain\Model\Pharmacy\PharmacyRepository;
use App\Exceptions\PharmacyNumberHasBeenAlreadyTakenException;
use App\Infrastructure\Services\PharmacyService;
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
        $pharmacyDto = new PharmacyDto('#1', 'pharmacy@gmail.com');

        $this->service->addPharmacy($pharmacyDto);
        /** @var Pharmacy $addedPharmacy */
        $addedPharmacy = $this->repository->findByNumber(new PharmacyNumber('#1'))->first();

        $this->assertEquals('#1', (string) $addedPharmacy->getNumber());
        $this->assertEquals('pharmacy@gmail.com', (string) $addedPharmacy->getEmail());
    }

    public function test_expects_exception_when_pharmacy_number_is_not_unique()
    {
        $dto = new PharmacyDto('#1', 'pharmacy@gmail.com');
        $this->service->addPharmacy($dto);

        $this->expectException(PharmacyNumberHasBeenAlreadyTakenException::class);

        $this->service->addPharmacy($dto);
    }

    public function test_can_update()
    {
        $dto = new PharmacyDto('#1', 'pharmacy@gmail.com');
        $this->service->addPharmacy($dto);
        $newDto = new PharmacyDto('#2', 'pharmacy2@gmail.com');
        $addedPharmacy = $this->repository->findByNumber(new PharmacyNumber('#1'))->first();

        $this->service->updatePharmacy((string) $addedPharmacy->getId(), $newDto);
        $updatedPharmacy = $this->repository->findById($addedPharmacy->getId());

        $this->assertEquals('#2', (string) $updatedPharmacy->getNumber());
        $this->assertEquals('pharmacy2@gmail.com', (string) $updatedPharmacy->getEmail());
    }

    public function test_can_delete_pharmacy()
    {
        $dto = new PharmacyDto('#1', 'pharmacy@gmail.com');

        $this->service->addPharmacy($dto);
        $addedPharmacy = $this->repository->findByNumber(new PharmacyNumber($dto->getPharmacyNumber()))->first();

        $this->service->deletePharmacy((string) $addedPharmacy->getId());

        $this->assertNotContains($addedPharmacy, $this->repository->all());
    }

    public function test_cant_delete_not_existing_pharmacy()
    {
        $this->expectException(NotFoundEntityException::class);

        $this->service->deletePharmacy('not-existing-pharmacy-id');
    }
}
