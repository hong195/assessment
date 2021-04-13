<?php


namespace Tests\Unit\Domain\Model\Review;

use Domain\Model\Review\Criterion;
use Domain\Model\Review\Exceptions\NotExistingSelectedOptionException;
use Domain\Model\Review\Option;
use PHPUnit\Framework\TestCase;

class CriterionTest extends TestCase
{
    public function test_get_criterion_selected_option()
    {
        $criterion = new Criterion('Ethics', [new Option('yes', 1), new Option('no', 0)], 'yes');

        $this->assertEquals(1, $criterion->getSelectedValue());
    }

    public function test_expects_exception_when_selected_option_not_exists()
    {
        $criterion = new Criterion('Ethics',
            [
                new Option('yes', 1), new Option('no', 0)
            ],
            'non-existing-option'
        );

        $this->expectException(NotExistingSelectedOptionException::class);

        $criterion->getSelectedValue();
    }
}
