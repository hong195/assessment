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
use Doctrine\ORM\EntityManagerInterface;

class CriterionService
{
    private CriterionRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(CriterionRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    public function getOptions(string $criterionId): \Doctrine\Common\Collections\Collection
    {
        /** @var Criterion $criterion */
        $criterion = $this->repository->findOrFail($criterionId);
        return $criterion->getOptions();
    }
    /**
     * @param string $name
     * @throws NotUniqueCriterionNameException
     */
    public function create(string $name, int $order = 0, $label = null)
    {
        if ($this->repository->findByName($name)) {
            throw new NotUniqueCriterionNameException();
        }

        $criterionId = CriterionId::next();
        $criterion = new Criterion($criterionId, $name, $order, $label);

        $this->repository->add($criterion);
        $this->em->flush();
    }

    /**
     * @param string $id
     * @param string $name
     * @throws NotFoundEntityException
     */
    public function update(string $id, string $name, int $order = 0, string $label = null)
    {
        $criterion = $this->repository->findById(new CriterionId($id));

        $criterion->changeName($name);

        if ($label) {
            $criterion->changeLabel($label);
        }

        $criterion->setOrder($order);
        $this->em->flush();
    }

    /**
     * @throws NotFoundEntityException
     */
    public function getOption(string $criterionId, string $optionId): ?\App\Domain\Model\Criterion\Option
    {
        /** @var Criterion $criterion */
        $criterion = $this->repository->findOrFail($criterionId);

        return $criterion->findOptionById(new OptionId($optionId));
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
        $this->em->flush();
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
        $this->em->flush();
    }

    /**
     * @param string $criterionId
     */
    public function removeCriterion(string $criterionId)
    {
        $criterion = $this->repository->findOrFail($criterionId);

        $this->repository->remove($criterion);
        $this->em->flush();
    }

    public function removeOption(string $criterionId, string $optionId)
    {
        /** @var Criterion $criterion */
        $criterion = $this->repository->findOrFail($criterionId);

        $criterion->removeOption(new OptionId($optionId));
        $this->em->flush();
    }
}
