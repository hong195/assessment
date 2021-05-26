<?php


namespace Domain\Model\Criterion;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Domain\Model\Criterion\Exceptions\CriterionException;

/**
 * @ORM\Entity
 */
final class Criterion
{
    /**
     * @ORM\Column (type="criterion_id")
     * @ORM\Id
     */
    private CriterionId $id;
    /**
     * @ORM\Column (type="string")
     */
    private string $name;
    /**
     * @ORM\OneToMany(targetEntity="Option", mappedBy="criterion")
     */
    private ArrayCollection $options;

    public function __construct(CriterionId $criteriaId, string $name)
    {
        $this->name = $name;
        $this->options = new ArrayCollection([]);
        $this->id = $criteriaId;
    }

    public function getOptions(): ArrayCollection
    {
        return $this->options;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param OptionId $optionId
     * @param string $label
     * @param float $value
     * @return Option
     * @throws CriterionException
     */
    public function addOption(OptionId $optionId, string $label, float $value): Option
    {
        foreach ($this->options as $option) {
            if ($option->isNameEquals($label)) {
                throw new CriterionException('The option name must be unique!');
            }
        }

        $option = new Option($optionId, $label, $value);
        $this->options->add($option);

        return $option;
    }

    public function removeOption(OptionId $optionId) : void
    {
        /** @var Option $option */
        foreach ($this->options as $k => $option) {
            if ($option->getId()->isEqual($optionId)) {
                unset($this->options[$k]);
                break;
            }
        }
    }

    public function removeAllOptions() : void
    {
        $this->options = new ArrayCollection([]);
    }
    /**
     * @param string $name
     * @throws CriterionException
     */
    public function assertNameIsNotEmpty(string $name)
    {
        if (empty($name)) {
            throw new CriterionException('Criterion name cannot be empty!');
        }
    }

    public function getId(): CriterionId
    {
        return $this->id;
    }
}
