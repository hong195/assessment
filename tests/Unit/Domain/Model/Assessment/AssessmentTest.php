<?php


namespace Tests\Unit\Domain\Model\Assessment;


use Domain\Model\Assessment\Criterion;
use Domain\Model\Assessment\Option;
use Domain\Model\Assessment\ReviewerId;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Domain\Model\Builders\AssessmentBuilder;

class AssessmentTest extends TestCase
{
    public function test_get_review_scored_point()
    {
        $criterion = [];

        $criterion[] = new Criterion('Ethics',
            [new Option('yes', 1), new Option('no', 0)],
            'yes');

        $criterion[] = new Criterion('Kindness',
            [new Option('yes', 1), new Option('no', 0)],
            'no');

        $criterion[] = new Criterion('Additional care',
            [new Option('partially', 0.5), new Option('no', 0)],
            'partially');

        $review = AssessmentBuilder::aReview()
            ->withEfficiencies($criterion)
            ->build();

        $this->assertEquals(1.5, $review->getScoredPoints());
        $this->assertCount(count($criterion), $review->getCriteria());
    }

    public function test_get_total_points()
    {
        $criterion = [];

        $criterion[] = new Criterion('Ethics',
            [
                new Option('yes', 10),
                new Option('no', 5)
            ],
            'yes');

        $criterion[] = new Criterion('Kindness',
            [
                new Option('yes', 15),
                new Option('no', 2)],
            'no');

        $criterion[] = new Criterion('Additional care',
            [
                new Option('partially', 0.7),
                new Option('no', 1)
            ],
            'partially');

        $review = AssessmentBuilder::aReview()
            ->withEfficiencies($criterion)
            ->build();

        $this->assertEquals(26, $review->getTotalPoints());
        $this->assertCount(count($criterion), $review->getCriteria());
    }

    public function test_can_assign_reviewer()
    {
        $assessment = AssessmentBuilder::aReview()->build();
        $reviewer = new ReviewerId(ReviewerId::next());

        $assessment->assignReviewer($reviewer);

        $this->assertEquals($reviewer, $assessment->getReviewer());
    }
}
