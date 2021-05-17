<?php


namespace Tests\Unit\Domain\Model\Criterion;


use Domain\Model\Criterion\Criterion;
use Domain\Model\Criterion\Exceptions\CriterionException;
use Domain\Model\Criterion\Option;
use Domain\Model\Criterion\OptionId;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Domain\Model\Builders\CriterionBuilder;

final class CriterionTest extends TestCase
{
    private Criterion $criterion;

    private OptionId $optionId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->criterion = CriterionBuilder::aCriterion()->build();
        $this->optionId = new OptionId(OptionId::next());
    }

    public function test_add_option()
    {
        $addedOption = $this->criterion->addOption($this->optionId, 'yes', 1);

        $this->assertInstanceOf(Option::class, $addedOption);
        $this->assertEquals($this->optionId, $addedOption->getId());
        $this->assertEquals('yes', $addedOption->getName());
        $this->assertEquals(1, $addedOption->getValue());
    }

    public function test_can_add_only_option_with_unique_name()
    {
        $anotherOptionId = new OptionId(OptionId::next());
        $this->criterion->addOption($this->optionId, 'no', 1);

        $this->expectException(CriterionException::class);

        $this->criterion->addOption($anotherOptionId, 'no', 1);
    }

    public function test_remove_an_option()
    {
        $addedOption = $this->criterion->addOption($this->optionId, 'no', 1);

        $this->criterion->removeOption($this->optionId);

        $this->assertNotContains($addedOption, $this->criterion->getOptions());
        $this->assertEmpty($this->criterion->getOptions());
    }

    public function test_remove_all_options()
    {
        $this->criterion->addOption($this->optionId, 'yes', 1);
        $this->criterion->addOption($this->optionId, 'no', 0);
        $this->criterion->addOption($this->optionId, 'partially', 0.5);

        $this->criterion->removeAllOptions();

        $this->assertEmpty($this->criterion->getOptions());
    }
}
