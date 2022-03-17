<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_get_request_current_user()
    {
        $response = $this->get('/api/users/7');
        

        // $response->assertStatus(200);
        $response->assertJson([
            "data" => 
            [
                "id" => 7, 
                "name" => "User3", 
                "car" => null, 
                "created_at" => null
            ]
        ]);
    }

    public function test_get_request_current_car()
    {
        $response = $this->get('/api/cars/2');
        

        // $response->assertStatus(200);
        $response->assertJson([
            "data" => 
            [
                "id" => 2, 
                "name" => "Car2", 
                "user_id" => 0, 
                "created_at" => null
            ]
        ]);
    }
}
