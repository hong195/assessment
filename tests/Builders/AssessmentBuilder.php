<?php


namespace Tests\Builders;


use App\Domain\Model\Assessment\Assessment;
use App\Domain\Model\Assessment\AssessmentId;
use App\Domain\Model\Assessment\Check;

class AssessmentBuilder
{

    private Check $check;

    private AssessmentId $id;

    private array $efficiencies;
    private \App\Domain\Model\FinalGrade\FinalGrade $analyses;

    public function __construct()
    {
        $this->id = AssessmentId::next();
        $this->check = CheckBuilder::aCheck()->build();
        $this->efficiencies = [];
        $this->analyses = FinalGradeBuilder::anAnalysis()->build();
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
        return new Assessment($this->id, $this->analyses, $this->check, $this->efficiencies);
    }
}
