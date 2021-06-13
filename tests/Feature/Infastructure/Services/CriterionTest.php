<?php


namespace Tests\Feature\Infastructure\Services;


use Doctrine\ORM\EntityManagerInterface;
use Domain\Model\Criterion\CriterionId;
use Domain\Model\Criterion\CriterionRepository;
use Domain\Model\Criterion\Option;
use Domain\Model\Criterion\OptionId;
use Infastructure\Exceptions\NotUniqueCriterionNameException;
use Infastructure\Services\CriterionService;
use Tests\Feature\DoctrineMigrationsTrait;
use Tests\TestCase;
use Tests\Unit\Domain\Model\Builders\CriterionBuilder;

/**
 * @group integration
 */
class CriterionTest extends TestCase
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
        $this->criterionService = new CriterionService($this->repository, $this->em);
    }

    public function test_can_create_a_criterion()
    {
        $id = CriterionId::next();
        $name = 'Ethics';

        $this->criterionService->create($id, $name);
        $this->em->flush();
        $createdCriterion = $this->repository->findById($id);

        $this->assertTrue($createdCriterion->getId()->isEqual($id));
        $this->assertEquals($name, $createdCriterion->getName());
    }

    public function test_criterion_name_must_be_unique()
    {
        $criterion = CriterionBuilder::aCriterion()->withName('aName')->build();
        $this->repository->add($criterion);
        $this->em->flush();

        $this->expectException(NotUniqueCriterionNameException::class);

        $this->criterionService->create(CriterionId::next(), 'aName');
    }

    public function test_can_update_name()
    {
        $id = CriterionId::next();
        $criterion = CriterionBuilder::aCriterion()
                ->withId($id)
                ->withName('aName')
                ->build();

        $this->repository->add($criterion);
        $this->criterionService->updateName($id, 'newName');
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
        $optionId = OptionId::next();
        $optionName = 'Yes';
        $optionValue = 1;

        $this->criterionService->addOption($criterion, $optionId, $optionName, $optionValue);
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
        $optionId = OptionId::next();

        $this->criterionService->addOption($criterion, $optionId, 'old option name', 1);
        $this->criterionService->updateOption($criterion, $optionId,'new option name', 2);
        $this->em->flush();
        $updatedCriterion = $this->repository->findById($id);
        $options = $updatedCriterion->getOptions();

        $this->assertEquals('new option name', $options->first()->getName());
        $this->assertEquals(2, $options->first()->getValue());
    }

    public function test_can_remove_an_option()
    {
        $id = CriterionId::next();
        $criterion = CriterionBuilder::aCriterion()
            ->withId($id)
            ->withName('aName')
            ->build();
        $this->repository->add($criterion);
        $optionId = OptionId::next();

        $this->criterionService->addOption($criterion, $optionId, 'old option name', 1);
        $this->criterionService->removeOption($criterion, $optionId);
        $this->em->flush();

        $updatedCriterion = $this->repository->findById($id);
        $options = $updatedCriterion->getOptions()->filter(function (Option $option) use ($id){
            return $option->getId()->isEqual($id);
        })->isEmpty();

        $this->assertTrue($options);
    }

    public function test_can_remove_all_options()
    {
        $id = CriterionId::next();
        $criterion = CriterionBuilder::aCriterion()
            ->withId($id)
            ->withName('aName')
            ->build();
        $this->repository->add($criterion);
        $optionId = OptionId::next();

        $this->criterionService->addOption($criterion, $optionId, 'old option name', 1);
        $this->criterionService->removeAllOptions($criterion);
        $this->em->flush();

        $this->assertEmpty($criterion->getOptions());
    }
}
