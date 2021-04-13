<?php


namespace Domain\Model\Review;


use Doctrine\Common\Collections\ArrayCollection;
use Domain\Model\Review\Exceptions\NotExistingSelectedOptionException;

final class Criterion
{
    private string $name;
    private ArrayCollection $options;
    private string $selected;
    private string $description;

    public function __construct(string $name, array $options, $selected = '', $description = '')
    {
        $this->name = $name;
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

    public function getDescription(): string
    {
        return $this->description;
    }
}
