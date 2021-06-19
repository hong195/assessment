<?php


namespace Infastructure\Services;


use Domain\Exceptions\NotFoundEntityException;
use Domain\Model\Criterion\Criterion;
use Domain\Model\Criterion\CriterionId;
use Domain\Model\Criterion\CriterionRepository;
use Domain\Model\Criterion\Exceptions\CriterionException;
use Domain\Model\Criterion\OptionId;
use Infastructure\Exceptions\NotUniqueCriterionNameException;

class CriterionService
{
    private CriterionRepository $repository;

    public function __construct(CriterionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $name
     * @throws NotUniqueCriterionNameException
     */
    public function create(string $name)
    {
        if ($this->repository->findByName($name)) {
            throw new NotUniqueCriterionNameException();
        }

        $criterionId = CriterionId::next();
        $criterion = new Criterion($criterionId, $name);

        $this->repository->add($criterion);
    }

    /**
     * @param string $id
     * @param string $name
     * @throws NotFoundEntityException
     */
    public function update(string $id, string $name)
    {
        $criterion = $this->repository->findById(new CriterionId($id));

        $criterion->changeName($name);
    }

    /**
     * @param Criterion $criterion
     * @param OptionId $optionId
     * @param string $name
     * @param float $value
     * @throws CriterionException
     */
    public function addOption(Criterion $criterion, OptionId  $optionId, string $name, float $value)
    {
        $criterion->addOption($optionId, $name, $value);
    }

    /**
     * @param Criterion $criterion
     * @param OptionId $optionId
     * @param string $name
     * @param float $value
     * @throws CriterionException
     */
    public function updateOption(Criterion $criterion, OptionId $optionId, string $name, float $value)
    {
        $criterion->updateOption($optionId, $name, $value);
    }

    /**
     * @param string $id
     */
    public function removeCriterion(string $id)
    {
        $criterion = $this->repository->findOrFail($id);

        $this->repository->remove($criterion);
    }

    public function removeOption(Criterion $criterion, OptionId $optionId)
    {
        $criterion->removeOption($optionId);
    }

    public function removeAllOptions(Criterion $criterion)
    {
        $criterion->removeAllOptions();
    }
}
