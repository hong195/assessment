<?php

namespace Tests\Unit\Domain\Check;

use Carbon\Carbon;
use Domain\Check\Entities\Attribute;
use Domain\Check\Entities\Check;
use Domain\Check\ValueObjects\AttributeId;
use Domain\Check\ValueObjects\CreationDate;
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
    private $userId;
    /**
     * @var CreationDate
     */
    private $creationDate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userId = new UserId(1);
        $this->creationDate = new CreationDate(now());

        $this->check = new Check(
            $this->userId,
            $this->creationDate,
            [],
            'Test Check',
        );
    }

    public function test_check_has_user_id()
    {
        $this->assertInstanceOf(UserId::class, $this->check->getUserId());
        $this->assertEquals((string) $this->userId, (string) $this->check->getUserId());
    }

    public function test_check_has_creation_date()
    {
        $this->assertInstanceOf(CreationDate::class, $this->check->getCreationDate());
        $this->assertEquals((string) $this->creationDate, (string) $this->check->getCreationDate());
    }

    public function test_can_get_list_of_attributes()
    {
        $this->assertEquals([], $this->check->getAttributes());
    }
}
