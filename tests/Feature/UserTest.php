<?php

namespace Tests\Feature;

use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_postUserSuccess()
    {
        $response = $this->postJson('/api/users', 
        ['login'=> 'Login',
        'password'=> 'Password',
        'email'=> 'email@email.email',
        'first_name'=> 'Name',
        'last_name'=> 'LastName',
        'role_id' => 0]);

        $response->assertStatus(201);
    }

    public function test_postUserFail_whenMissingColumn()
    {
        $response = $this->postJson('/api/users', 
        [
        'password'=> 'Password',
        'email'=> 'email@email.email',
        'first_name'=> 'Name',
        'last_name'=> 'LastName',
        'role_id' => 0]);

        $response->assertStatus(422);          
    }

    public function test_postUserFail_whenEmptyColumn()
    {
        $response = $this->postJson('/api/users', 
        [
        'login'=> '',
        'password'=> 'Password',
        'email'=> 'email@email.email',
        'first_name'=> 'Name',
        'last_name'=> 'LastName',
        'role_id' => 0]);

        $response->assertStatus(422);          
    }

    public function test_getUsers_status405()
    {
        $response = $this->get('/api/users');

        $response->assertStatus(405);
    }

    public function testGetFilms()
    {
        $users = User::factory()->count(3)->make();
        
        $this->assertDatabaseHas(
            'users',
            ['login' => $users[0]->login]
        );
    }
}
