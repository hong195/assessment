<?php


namespace Domain\Model\Assessment;


use Doctrine\Common\Collections\ArrayCollection;
use Domain\Model\Assessment\Exceptions\NotExistingSelectedOptionException;

final class Criterion
{
    private string $name;
    private ArrayCollection $options;
    private string $selected;
    private string $description;

    public function __construct(string $criterion, array $options, $selected, $description = '')
    {
        $this->name = $criterion;
        $this->options = new ArrayCollection($options);
        $this->selected = $selected;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    public function getOptions(): ArrayCollection
    {
        return $this->options;
    }

    /**
     * @return mixed
     * @throws NotExistingSelectedOptionException
     */
    public function getSelectedValue() : float
    {
        foreach ($this->options as $option) {
            if ($option->getName() === $this->selected) {
                return $option->getValue();
            }
        }

        throw new NotExistingSelectedOptionException;
    }

    public function getMaxPoint() : float
    {
        return array_reduce($this->options->toArray(),function($carry, Option $option) {
            $carry = max($carry, $option->getValue());
            return $carry;
        }, 0);
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
