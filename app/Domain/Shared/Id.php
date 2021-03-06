<?php


namespace App\Domain\Shared;

use Ramsey\Uuid\Uuid;

class Id
{
    protected string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function __toString() : string
    {
        return $this->id;
    }

    public static function next()
    {
        return new static(Uuid::uuid4()->toString());
    }

    public function isEqual(self $userId) : bool
    {
        return $this->id === (string) $userId;
    }
}
