<?php


namespace Domain\User\Entities;


use Domain\User\ValueObjects\Email;
use Domain\User\ValueObjects\FullName;
use Domain\User\ValueObjects\UserId;

class User
{
    /**
     * @var UserId
     */
    private $id;
    /**
     * @var Email
     */
    private $email;
    /**
     * @var string
     */
    private $name;

    public function __construct(UserId $userId, Email $email, FullName $name)
    {
        $this->id = $userId;
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * @return UserId
     */
    public function getId(): UserId
    {
        return $this->id;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return FullName
     */
    public function getFullName(): FullName
    {
        return $this->name;
    }

    public static function create(UserId $userId, Email $email, FullName $name): self
    {
        return new self($userId, $email, $name);
    }
}
