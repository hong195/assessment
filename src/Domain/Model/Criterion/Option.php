<?php


namespace Domain\Model\Criterion;


use Doctrine\ORM\Mapping as ORM;
use Domain\Model\Criterion\Exceptions\CriterionException;

/**
 * Class Option
 * @ORM\Entity
 */
class Option
{
    /**
     * @ORM\Column (type="criterion_option_id")
     * @ORM\Id
     */
    private OptionId $id;
    /**
     * @ORM\Column (type="string")
     */
    private string $name;
    /**
     * @ORM\Column (type="float", nullable=true)
     */
    private float $value;
    /**
     * @ORM\ManyToOne(targetEntity="Criterion", inversedBy="options")
     * @ORM\JoinColumn(name="criterion_id", referencedColumnName="id")
     */
    private Criterion $criterion;

    /**
     * Option constructor.
     * @param OptionId $optionId
     * @param Criterion $criterion
     * @param string $name
     * @param float|int $value
     * @throws CriterionException
     */
    public function __construct(OptionId $optionId, Criterion $criterion, string $name, float $value = 0)
    {
        $this->assertNotEmpty($name);
        $this->assertValueNotNegative($value);
        $this->id = $optionId;
        $this->name = $name;
        $this->value = $value;
        $this->criterion = $criterion;
    }

    /**
     * @param string $name
     * @throws CriterionException
     */
    public function changeName(string $name)
    {
        $this->assertNotEmpty($name);
        $this->name = $name;
    }

    /**
     * @param float $value
     * @throws CriterionException
     */
    public function changeValue(float $value)
    {
        $this->assertValueNotNegative($value);
        $this->value = $value;
    }
    /**
     * @param string $name
     * @throws CriterionException
     */
    private function assertNotEmpty(string $name)
    {
        if (empty($name)) {
            throw new CriterionException('Option name cannot be empty!');
        }
    }

    /**
     * @param float $value
     * @throws CriterionException
     */
    private function assertValueNotNegative(float $value)
    {
        if ($value < 0) {
            throw new CriterionException('Option value cannot be negative!');
        }
    }

    public function isNameEquals(string $name) : bool
    {
        return $name === $this->name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getId(): OptionId
    {
        return $this->id;
    }
}
