<?php


namespace Tests\Feature\Infastructure\Persistence;


use Domain\Model\Criterion\CriterionRepository;
use Tests\TestCase;
use Tests\Unit\Domain\Model\Builders\CriterionBuilder;

abstract class AbstractCriterionTest extends TestCase
{
    private CriterionRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getRepository();
    }

    abstract protected function getRepository() : CriterionRepository;

    public function test_can_add_a_criterion()
    {
        $criterionA = CriterionBuilder::aCriterion()->build();
        $criterionB = CriterionBuilder::aCriterion()->build();

        $this->repository->add($criterionA);
        $this->repository->add($criterionB);

        $this->assertNotEmpty($this->repository->all());
        $this->assertContains($criterionA, $this->repository->all());
        $this->assertContains($criterionB, $this->repository->all());
    }

    public function test_can_get_all_criteria()
    {
        $criterionA = CriterionBuilder::aCriterion()->build();
        $criterionB = CriterionBuilder::aCriterion()->build();

        $this->repository->add($criterionA);
        $this->repository->add($criterionB);

        $this->assertNotEmpty($this->repository->all());
        $this->assertCount(2, $this->repository->all());
    }

    public function test_can_remove_a_criterion()
    {
        $criterion = CriterionBuilder::aCriterion()->build();

        $this->repository->add($criterion);
        $this->repository->remove($criterion->getId());

        $this->assertNotContains($criterion, $this->repository->all());
    }

    public function test_can_find_by_id()
    {
        $criterion = CriterionBuilder::aCriterion()->build();

        $this->repository->add($criterion);
        $found = $this->repository->findById($criterion->getId());

        $this->assertEquals($found, $criterion);
    }
}
