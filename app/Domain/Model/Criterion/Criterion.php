<?php


namespace App\Domain\Model\Criterion;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Exceptions\DomainException;
use App\Exceptions\CriterionException;

/**
 * @ORM\Entity
 */
class Criterion
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
     * @ORM\OneToMany(targetEntity="Option", mappedBy="criterion", cascade={"persist","remove"})
     */
    private Collection $options;

    public function __construct(CriterionId $criteriaId, string $name)
    {
        $this->name = $name;
        $this->options = new ArrayCollection([]);
        $this->id = $criteriaId;
    }

    public function getOptions(): Collection
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

        $option = new Option($optionId, $this, $label, $value);
        $this->options->add($option);

        return $option;
    }

    /**
     * @param OptionId $optionId
     * @param string $name
     * @param float $value
     * @throws CriterionException
     */
    public function updateOption(OptionId $optionId, string $name, float $value)
    {
        foreach ($this->options as $k => $option) {
            if ($option->getId()->isEqual($optionId)) {
                $this->options[$k]->changeName($name);
                $this->options[$k]->changeValue($value);
                break;
            }if ($option->isNameEquals($name)) {
                throw new CriterionException('The option name is already taken!');
            }
        }
    }

    public function findOptionById(OptionId $optionId) : ?Option
    {
        /** @var Option $option */
        foreach ($this->options as $option) {
            if ($option->getId()->isEqual($optionId)) {
                return $option;
            }
        }

        return null;
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

    public function changeName(string $name)
    {
        $this->name = $name;
    }
}
