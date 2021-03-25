<?php


namespace Domain\Check\Entities;


use Domain\Check\ValueObjects\CreationDate;
use Domain\User\Entities\User;
use Domain\User\Exception\TheSameUserException;


class Check
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var CreationDate
     */
    private $creationDate;
    /**
     * @var array
     */
    private $criteria;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $amount;
    /**
     * @var array
     */
    private $nonPrimeAttributes;
    /**
     * @var User
     */
    private $reviewer;


    /**
     * Check constructor.
     * @param User $user
     * @param User $reviewer
     * @param CreationDate $creationDate
     * @param array $criteria
     * @param string $name
     * @param int $amount
     * @throws TheSameUserException
     */
    public function __construct(User $user,
                                User $reviewer,
                                CreationDate $creationDate,
                                array $criteria = [],
                                $name = '',
                                $amount = null)
    {
        $this->validateUserAndReviewer($user, $reviewer);

        $this->user = $user;
        $this->reviewer = $reviewer;
        $this->creationDate = $creationDate;
        $this->criteria = $criteria;
        $this->name = $name;
        $this->amount = $amount;
    }

    public function getCreationDate(): CreationDate
    {
        return $this->creationDate;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Attribute[]
     */
    public function getCriteria(): array
    {
        return $this->criteria;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getScoredPoints(): float
    {
        $scored = 0;

        foreach ($this->getCriteria() as $attribute) {
            $scored += $attribute->getValue();
        }

        return $scored;
    }

    /**
     * @param User $user
     * @param User $reviewer
     * @throws TheSameUserException
     */
    private function validateUserAndReviewer(User $user, User $reviewer)
    {
        if ($user->getId()->isEqual($reviewer->getId())) {
            throw new TheSameUserException();
        }
    }
}
