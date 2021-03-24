<?php


namespace Domain\Check\Entities;


use Domain\Check\ValueObjects\CreationDate;
use Domain\User\ValueObjects\UserId;
use Domain\Check\Entities\Attribute;

class Check
{
    /**
     * @var UserId
     */
    private $userId;
    /**
     * @var CreationDate
     */
    private $creationDate;
    /**
     * @var array
     */
    private $attributes;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $amount;

    public function __construct(UserId $userId,
                                CreationDate $creationDate,
                                array $attributes = [],
                                string $name = '',
                                string $amount = '')
    {
        $this->userId = $userId;
        $this->creationDate = $creationDate;
        $this->attributes = $attributes;
        $this->name = $name;
        $this->amount = $amount;
    }

    public function getCreationDate(): CreationDate
    {
        return $this->creationDate;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return Attribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
