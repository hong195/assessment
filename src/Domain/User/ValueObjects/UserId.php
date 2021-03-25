<?php


namespace Domain\User\ValueObjects;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserId
{
    private $id;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public function __toString() : string
    {
        return $this->id->toString();
    }

    public static function next(): \Ramsey\Uuid\UuidInterface
    {
        return Uuid::uuid4();
    }

    public function isEqual(self $userId) : bool
    {
        return (string) $userId === (string) $this;
    }
}
