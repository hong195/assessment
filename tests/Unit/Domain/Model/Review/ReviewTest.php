<?php


namespace Tests\Unit\Domain\Model\Review;


use Domain\Model\Review\Criterion;
use Domain\Model\Review\Exceptions\ReviewerEqualsToPharmacistException;
use Domain\Model\Review\Option;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Domain\Model\Builders\ReviewBuilder;
use Tests\Unit\Domain\Model\Builders\UserBuilder;

class ReviewTest extends TestCase
{
    public function test_reviewer_and_user_cannot_be_the_same_person()
    {
        $reviewerId = UserBuilder::aUser()->build()->getId();

        $this->expectException(ReviewerEqualsToPharmacistException::class);

        ReviewBuilder::aReview()->withUserId($reviewerId)->withReviewerId($reviewerId)->build();
    }

    public function test_get_review_scored_point()
    {
        $this->assertTrue(true);
        $criteria = [];

        $criteria[] = new Criterion('Ethics',
            [new Option('yes', 1), new Option('no', 0)],
            'yes');

        $criteria[] = new Criterion('Kindness',
            [new Option('yes', 1), new Option('no', 0)],
            'no');

        $criteria[] = new Criterion('Additional care',
            [new Option('partially', 0.5), new Option('no', 0)],
            'partially');

        $review = ReviewBuilder::aReview()
            ->withCriteria($criteria)
            ->build();

        $this->assertEquals(1.5, $review->getScoredPoints());
    }

    public function test_get_total_points()
    {
        $this->assertTrue(true);
        $criteria = [];

        $criteria[] = new Criterion('Ethics',
            [
                new Option('yes', 10),
                new Option('no', 5)
            ],
            'yes');

        $criteria[] = new Criterion('Kindness',
            [
                new Option('yes', 15),
                new Option('no', 2)],
            'no');

        $criteria[] = new Criterion('Additional care',
            [
                new Option('partially', 0.7),
                new Option('no', 1)
            ],
            'partially');

        $review = ReviewBuilder::aReview()
            ->withCriteria($criteria)
            ->build();

        $this->assertEquals(26, $review->getTotal());
    }
}
