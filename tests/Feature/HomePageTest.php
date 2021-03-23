<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_index_page() : void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
