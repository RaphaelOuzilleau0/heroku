<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private $token = "";
    
    /*****************************
    * Store
    ****************************/

    public function testPostUsers_shouldReturnLogin_test1()
    {
        $response = $this->postJson('/api/users/create', ['login' => 'test1', 'password' => 'Test1234', 'email' => 'test@gmail.com', 'last_name' => 'test', 'first_name' => 'test', 'role_id' => 1]);

        $response
            ->assertStatus(201);
    }

    public function testPostUsers__withoutAnyInfo_shouldReturnError422()
    {
        $response = $this->postJson('/api/users/create');
        $response
            ->assertStatus(422);
    }

    /*****************************
    * Database
    ****************************/

    public function testCreateUser_DatabaseHasCreatedUser()
    {
        $users = User::factory()->count(1)->create();

        $this->assertDatabaseHas(
            'users',
            ['login' => $users[0]->login]
        );
    }
}