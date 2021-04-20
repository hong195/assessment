<?php


namespace Tests\Unit\Domain\Model\Assessment;


use Domain\Model\Assessment\Efficiency;
use Domain\Model\Assessment\Option;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Domain\Model\Builders\AssessmentBuilder;
use Tests\Unit\Domain\Model\Builders\UserBuilder;

class AssessmentTest extends TestCase
{
    public function test_get_review_scored_point()
    {
        $this->assertTrue(true);
        $efficiency = [];

        $efficiency[] = new Efficiency('Ethics',
            [new Option('yes', 1), new Option('no', 0)],
            'yes');

        $efficiency[] = new Efficiency('Kindness',
            [new Option('yes', 1), new Option('no', 0)],
            'no');

        $efficiency[] = new Efficiency('Additional care',
            [new Option('partially', 0.5), new Option('no', 0)],
            'partially');

        $review = AssessmentBuilder::aReview()
            ->withEfficiencies($efficiency)
            ->build();

        $this->assertEquals(1.5, $review->getScoredPoints());
        $this->assertCount(count($efficiency), $review->getEfficiencies());
    }

    public function test_get_total_points()
    {
        $efficiency = [];

        $efficiency[] = new Efficiency('Ethics',
            [
                new Option('yes', 10),
                new Option('no', 5)
            ],
            'yes');

        $efficiency[] = new Efficiency('Kindness',
            [
                new Option('yes', 15),
                new Option('no', 2)],
            'no');

        $efficiency[] = new Efficiency('Additional care',
            [
                new Option('partially', 0.7),
                new Option('no', 1)
            ],
            'partially');

        $review = AssessmentBuilder::aReview()
            ->withEfficiencies($efficiency)
            ->build();

        $this->assertEquals(26, $review->getTotalPoints());
        $this->assertCount(count($efficiency), $review->getEfficiencies());
    }

    public function test_can_assign_a_reviewer()
    {
        $review = AssessmentBuilder::aReview()->build();
        $reviewerId = UserBuilder::aUser()->build()->getId();

        $review->setReviewerId($reviewerId);

        $this->assertEquals($reviewerId, $review->getReviewerId());
    }
}
