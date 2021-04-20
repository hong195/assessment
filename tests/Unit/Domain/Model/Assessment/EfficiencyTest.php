<?php


namespace Tests\Unit\Domain\Model\Assessment;

use Domain\Model\Assessment\Efficiency;
use Domain\Model\Assessment\Exceptions\NotExistingSelectedOptionException;
use Domain\Model\Assessment\Option;
use PHPUnit\Framework\TestCase;

class EfficiencyTest extends TestCase
{
    public function test_get_criterion_selected_option()
    {
        $efficiency = new Efficiency('Ethics', [
            new Option('yes', 1),
            new Option('no', 0)
        ], 'yes');

        $this->assertEquals(1, $efficiency->getSelectedValue());
    }

    public function test_get_max_available_option_value()
    {
        $efficiency = new Efficiency('Ethics', [
            new Option('yes', 7.5),
            new Option('no', 7)
        ], 'yes');

        $this->assertEquals(7.5, $efficiency->getMaxPoint());
    }

    public function test_expects_exception_when_selected_option_not_exists()
    {
        $efficiency = new Efficiency('Ethics',
            [
                new Option('yes', 1), new Option('no', 0)
            ],
            'non-existing-option'
        );

        $this->expectException(NotExistingSelectedOptionException::class);

        $efficiency->getSelectedValue();
    }
}
