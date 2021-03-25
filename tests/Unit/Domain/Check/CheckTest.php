<?php

namespace Tests\Unit\Domain\Check;

use Domain\Check\Entities\Attribute;
use Domain\Check\Entities\Check;
use Domain\Check\ValueObjects\AttributeId;
use Domain\Check\ValueObjects\CreationDate;
use Domain\User\Entities\User;
use Domain\User\ValueObjects\Email;
use Domain\User\ValueObjects\FullName;
use Domain\User\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

class CheckTest extends TestCase
{
    /**
     * @var Check
     */
    private $check;
    /**
     * @var UserId
     */
    private $user;

    private $reviewer;
    /**
     * @var CreationDate
     */
    private $creationDate;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->reviewer = $this->createUser();

        $this->creationDate = new CreationDate(now());

        $attributesList = [
            new Attribute(new AttributeId(1), 2, 'Test Description'),
            new Attribute(new AttributeId(2), 2,'Test Description2'),
            new Attribute(new AttributeId(3), 2, 'Test Description3'),
        ];

        $this->check = new Check(
            $this->user,
            $this->reviewer,
            $this->creationDate,
            $attributesList,
        );
    }

    private function createUser($email = 'test@gmail.com')
    {
        return new User(
            new UserId(UserId::next()),
            new Email($email),
            new FullName('test', 'test', 'test'),
        );
    }

    public function test_check_has_creation_date()
    {
        $this->assertInstanceOf(CreationDate::class, $this->check->getCreationDate());
        $this->assertEquals((string) $this->creationDate, (string) $this->check->getCreationDate());
    }

    public function test_check_scored_points()
    {
        $this->assertEquals(6, $this->check->getScoredPoints());
    }
}
