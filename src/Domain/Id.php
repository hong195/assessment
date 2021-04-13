<?php


namespace Domain;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class Id
{
    private UuidInterface $id;

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
