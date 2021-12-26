<?php


namespace App\Domain\Model\Assessment;


use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Model\Assessment\Exceptions\NotExistingSelectedOptionException;
use JetBrains\PhpStorm\Pure;

final class Criterion
{
    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $label;

    /**
     * @var ArrayCollection
     */
    private ArrayCollection $options;

    /**
     * @var string
     */
    private string $selected;

    /**
     * @var string|mixed
     */
    private string $description;

    #[Pure] public function __construct(string $name,
                                        array  $options,
                                        string $selected,
                                        string $description = '',
                                        string $label = '')
    {
        $this->name = $name;
        $this->options = new ArrayCollection($options);
        $this->selected = $selected;
        $this->description = $description;
        $this->label = $label;
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function getOptions(): ArrayCollection
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getSelected() : string
    {
        return $this->selected;
    }
    /**
     * @return int|float
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
        return array_reduce($this->options->toArray(),
            fn($carry, Option $option) => max($carry, $option->getValue()), 0);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }
}
