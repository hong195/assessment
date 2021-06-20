<?php


namespace Tests\Feature\Infastructure\Services;


use App\Http\DataTransferObjects\CriterionOptionDto;
use Doctrine\ORM\EntityManagerInterface;
use Domain\Exceptions\NotFoundEntityException;
use Domain\Model\Criterion\CriterionId;
use Domain\Model\Criterion\CriterionRepository;
use Domain\Model\Criterion\Option;
use Infastructure\Exceptions\NotUniqueCriterionNameException;
use Infastructure\Services\CriterionService;
use Tests\Feature\DoctrineMigrationsTrait;
use Tests\TestCase;
use Tests\Unit\Domain\Model\Builders\CriterionBuilder;

/**
 * @group integration
 */
class CriterionServiceTest extends TestCase
{
    use DoctrineMigrationsTrait;

    private CriterionRepository $repository;

    private EntityManagerInterface $em;

    private CriterionService $criterionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resetMigrations();

        $this->repository = app()->make(CriterionRepository::class);
        $this->em = app()->make(EntityManagerInterface::class);
        $this->criterionService = new CriterionService($this->repository);
    }

    public function test_can_create_a_criterion()
    {
        $name = 'Ethics';

        $this->criterionService->create($name);
        $this->em->flush();
        $createdCriterion = $this->repository->findByName($name);

        $this->assertEquals($name, $createdCriterion->getName());
    }

    public function test_criterion_name_must_be_unique()
    {
        $criterion = CriterionBuilder::aCriterion()->withName('aName')->build();
        $this->repository->add($criterion);
        $this->em->flush();

        $this->expectException(NotUniqueCriterionNameException::class);

        $this->criterionService->create('aName');
    }

    public function test_can_update_name()
    {
        $id = CriterionId::next();
        $criterion = CriterionBuilder::aCriterion()
                ->withId($id)
                ->withName('aName')
                ->build();

        $this->repository->add($criterion);
        $this->criterionService->update($id, 'newName');
        $this->em->flush();
        $updatedCriterion = $this->repository->findById($id);

        $this->assertEquals('newName', $updatedCriterion->getName());
    }

    public function test_can_add_an_option()
    {
        $id = CriterionId::next();
        $criterion = CriterionBuilder::aCriterion()
            ->withId($id)
            ->withName('aName')
            ->build();
        $this->repository->add($criterion);
        $optionName = 'Yes';
        $optionValue = 1;
        $criterionOptionDto = new CriterionOptionDto($optionName, $optionValue);

        $this->criterionService->addOption((string) $id, $criterionOptionDto);
        $this->em->flush();

        $updatedCriterion = $this->repository->findById($id);
        $options = $updatedCriterion->getOptions();

        $this->assertEquals($optionName, $options->first()->getName());
        $this->assertEquals($optionValue, $options->first()->getValue());
    }

    public function test_can_update_option()
    {
        $id = CriterionId::next();
        $criterion = CriterionBuilder::aCriterion()
            ->withId($id)
            ->withName('aName')
            ->build();
        $this->repository->add($criterion);

        $criterionOptionDto = new CriterionOptionDto('old-name', 0.6);
        $this->criterionService->addOption((string) $id, $criterionOptionDto);
        $updatedCriterion = $this->repository->findById($id);
        $options = $updatedCriterion->getOptions();

        $addedOption = $updatedCriterion->getOptions()->filter(function (Option $option) {
            return $option->getName() === 'old-name';
        })->first();

        $updatedCriterionDto = new CriterionOptionDto('new-name', 1);
        $this->criterionService->updateOption((string) $id, $addedOption->getId(), $updatedCriterionDto);
        $this->em->flush();


        $this->assertEquals('new-name', $options->first()->getName());
        $this->assertEquals(1, $options->first()->getValue());
    }

    public function test_can_remove_an_option()
    {
        $id = CriterionId::next();
        $criterion = CriterionBuilder::aCriterion()
            ->withId($id)
            ->withName('aName')
            ->build();
        $this->repository->add($criterion);
        $criterionOptionDto = new CriterionOptionDto('old-name', 0.6);

        $this->criterionService->addOption((string) $id, $criterionOptionDto);
        $addedOption = $criterion->getOptions()->filter(function (Option $option) {
            return $option->getName() === 'old-name';
        })->first();

        $this->criterionService->removeOption($criterion, $addedOption->getId());
        $this->em->flush();

        $updatedCriterion = $this->repository->findById($id);
        $options = $updatedCriterion->getOptions()->filter(function (Option $option) use ($id){
            return $option->getId()->isEqual($id);
        })->isEmpty();

        $this->assertTrue($options);
    }

    public function test_can_remove_criterion()
    {
        $id = CriterionId::next();
        $criterion = CriterionBuilder::aCriterion()
            ->withId($id)
            ->withName('aName')
            ->build();
        $this->repository->add($criterion);

        $this->criterionService->removeCriterion($id);
        $this->em->flush();

        $this->assertNull($this->repository->findById($id));
    }

    public function test_cannot_remove_not_existing_criterion()
    {
        $id = CriterionId::next();

        $this->expectException(NotFoundEntityException::class);

        $this->criterionService->removeCriterion($id);
    }
}
