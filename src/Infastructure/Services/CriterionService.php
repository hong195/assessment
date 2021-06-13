<?php


namespace Infastructure\Services;


use Domain\Exceptions\NotFoundEntityException;
use Domain\Model\Criterion\Criterion;
use Domain\Model\Criterion\CriterionId;
use Domain\Model\Criterion\CriterionRepository;
use Domain\Model\Criterion\Option;
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
     * @param CriterionId $id
     * @param string $name
     * @throws NotUniqueCriterionNameException
     */
    public function create(CriterionId $id, string $name)
    {
        if ($this->repository->findByName($name)) {
            throw new NotUniqueCriterionNameException();
        }

        $criterion = new Criterion($id, $name);

        $this->repository->add($criterion);
    }

    /**
     * @param CriterionId $id
     * @param string $name
     * @throws NotFoundEntityException
     */
    public function updateName(CriterionId $id, string $name)
    {
        $criterion = $this->repository->findById($id);

        if (!$criterion) {
            throw new NotFoundEntityException();
        }

        $criterion->changeName($name);
    }

    /**
     * @param Criterion $criterion
     * @param OptionId $optionId
     * @param string $name
     * @param float $value
     * @throws \Domain\Model\Criterion\Exceptions\CriterionException
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
     * @throws \Domain\Model\Criterion\Exceptions\CriterionException
     */
    public function updateOption(Criterion $criterion, OptionId $optionId, string $name, float $value)
    {
        $criterion->updateOption($optionId, $name, $value);
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
