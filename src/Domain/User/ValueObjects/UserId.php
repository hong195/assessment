<?php


namespace Domain\User\ValueObjects;


class UserId
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function __toString() : string
    {
        return $this->id;
    }
}
