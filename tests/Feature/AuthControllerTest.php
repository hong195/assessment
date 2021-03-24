<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;


    private $jwtToken;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => bcrypt('123456')
        ]);

        $this->jwtToken = auth()->tokenById($user->id);
    }

//    public function test_successfully_login_via_api()
//    {
//        $response = $this->post('api/auth/login', [
//            'email' => 'test@gmail.com',
//            'password' => '123456'
//        ]);
//
//        $response->assertOk();
//    }
//
//    public function test_can_user_get_info_about_himself()
//    {
//        $response = $this->post('api/auth/me', [], [
//            'Authorization' => 'Bearer ' . $this->jwtToken
//        ]);
//
//        $response->assertJsonStructure(['user' => []]);
//    }
//
//    public function test_successful_logout()
//    {
//        $response = $this->post('api/auth/logout', [], [
//            'Authorization' => 'Bearer ' . $this->jwtToken
//        ]);
//
//        $response->assertSee(trans('auth.logout'));
//    }
//
//    public function test_successful_token_refresh()
//    {
//        $response = $this->post('api/auth/refresh', [], [
//            'Authorization' => 'Bearer ' . $this->jwtToken
//        ]);
//
//        $response->assertJsonStructure(['access_token']);
//    }
}
