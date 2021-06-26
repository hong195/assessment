<?php


namespace Tests\Unit\Domain\Model\User;

use App\Exceptions\EmptyLoginException;
use App\Domain\Model\User\Login;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    public function test_login_cant_be_empty()
    {
        $this->expectException(EmptyLoginException::class);

        new Login('');
    }

    public function test_login_must_be_at_least_4_characters()
    {
        $login = new Login('test');

        $this->assertEquals( strlen('test'), strlen($login));
    }
}
