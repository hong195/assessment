<?php


namespace App\Infrastructure\Services;


use App\DataTransferObjects\CriterionOptionDto;
use App\Exceptions\NotFoundEntityException;
use App\Domain\Model\Criterion\Criterion;
use App\Domain\Model\Criterion\CriterionId;
use App\Domain\Model\Criterion\CriterionRepository;
use App\Exceptions\CriterionException;
use App\Domain\Model\Criterion\OptionId;
use App\Exceptions\NotUniqueCriterionNameException;

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
     * @param string $criterionId
     * @param CriterionOptionDto $dto
     * @throws CriterionException
     */
    public function addOption(string $criterionId, CriterionOptionDto $dto)
    {
        /** @var Criterion $criterion */
        $criterion = $this->repository->findOrFail($criterionId);
        $optionId = OptionId::next();

        $criterion->addOption($optionId, $dto->getName(), $dto->getValue());
    }

    /**
     * @param string $criterionId
     * @param CriterionOptionDto $dto
     * @throws CriterionException
     * @throws NotFoundEntityException
     */
    public function updateOption(string $criterionId, string $optionId, CriterionOptionDto $dto)
    {
        /** @var Criterion $criterion */
        $criterion = $this->repository->findOrFail($criterionId);

        $option = $criterion->findOptionById(new OptionId($optionId));

        if (!$option) {
            throw new NotFoundEntityException();
        }

        $criterion->updateOption($option->getId(), $dto->getName(), $dto->getValue());
    }

    /**
     * @param string $criterionId
     */
    public function removeCriterion(string $criterionId)
    {
        $criterion = $this->repository->findOrFail($criterionId);

        $this->repository->remove($criterion);
    }

    public function removeOption(string $criterionId, string $optionId)
    {
        $criterion = $this->repository->findOrFail($criterionId);

        $criterion->removeOption(new OptionId($optionId));
    }
}
