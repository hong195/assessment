<?php


namespace Tests\Unit\Domain\Model\Builders;


use Domain\Model\Assessment\Check;
use Domain\Model\Assessment\Assessment;
use Domain\Model\Assessment\AssessmentId;
use Domain\Model\User\UserId;

class AssessmentBuilder
{

    private Check $check;

    private AssessmentId $id;

    private array $efficiencies;

    public function __construct()
    {
        $this->id = AssessmentId::next();
        $this->check = CheckBuilder::aCheck()->build();
        $this->efficiencies = [];
    }

    public static function aReview(): AssessmentBuilder
    {
        return new self();
    }

    public function withId(AssessmentId $id) : self
    {
        $this->id = $id;
        return $this;
    }

    public function withEfficiencies(array $efficiency): AssessmentBuilder
    {
        $this->efficiencies = $efficiency;
        return $this;
    }

    public function withCheck(Check $check): AssessmentBuilder
    {
        $this->check = $check;

        return $this;
    }

    public function build(): Assessment
    {
        return new Assessment($this->id, $this->check, $this->efficiencies);
    }
}
