<?php


namespace Tests\Unit\Domain\Model\Builders;


use Domain\Model\Criterion\Criterion;
use Domain\Model\Criterion\CriterionId;
use Domain\Model\Criterion\Option;

class CriterionBuilder
{
    private string $name;

    private CriterionId $criterionId;

    public function __construct()
    {
        $this->name = 'Ethics';
        $this->criterionId = new CriterionId(CriterionId::next());
    }

    public static function aCriterion(): self
    {
        return new self();
    }

    public function withId(CriterionId $criterionId): self
    {
        $this->criterionId = $criterionId;

        return $this;
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function build(): Criterion
    {
        return new Criterion($this->criterionId, $this->name);
    }
}
