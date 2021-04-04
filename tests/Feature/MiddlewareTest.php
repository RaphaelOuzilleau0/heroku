<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private $adminToken = '';
    private $memberToken = '';

    public function setUp() : void
    {
        parent::setUp();

        $adminResponse = $this->postJson('/api/users/create', ['login' => 'test101', 'password' => 'justTesting', 'email' => 'test101@gmail.com', 'last_name' => 'test', 'first_name' => 'test', 'role_id' => 1]);
        $this->adminToken = $adminResponse['token'];

        $memberResponse = $this->postJson('/api/users/create', ['login' => 'test202', 'password' => 'justTesting2', 'email' => 'test202@gmail.com', 'last_name' => 'test', 'first_name' => 'test', 'role_id' => 2]);
        $this->memberToken = $memberResponse['token'];
    }

    /*****************************
     * Films
     ****************************/
    private function createFilm()
    {
        return $this->withHeaders(['Accept' => 'application/json','Authorization' => 'Bearer ' . $this->adminToken])
                        ->post(
                            '/api/films',
                            ['title' => 'title',
                            'release_year' => 2000,
                            'length' => 123,
                            'description' => 'description',
                            'rating' => 'G',
                            'language_id' => 1,
                            'special_features' => 'Deleted Scenes',
                            'image' => '']
                        );
    }

    public function testCreateFilm_shouldReturn200_whenEveryFieldAreFilled()
    {
        $response = $this->createFilm();
        $response->assertStatus(201);
    }

    public function testCreateFilm_shouldReturn422_whenNotEveryFieldAreFilled()
    {
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization' => 'Bearer ' . $this->adminToken])
                        ->post(
                            '/api/films',
                            ['title' => 'title1',
                            'release_year' => 2000,
                            'length' => 123,
                            'description' => 'description']
                        );

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['message' => 'The given data was invalid.']);
    }

    public function testCreateFilm_shouldReturn403_whenNotAdmin()
    {
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization' => 'Bearer ' . $this->memberToken])
                        ->post(
                            '/api/films',
                            ['title' => 'title',
                            'release_year' => 2000,
                            'length' => 123,
                            'description' => 'description',
                            'rating' => 'G',
                            'language_id' => 1,
                            'special_features' => 'Deleted Scenes',
                            'image' => '']
                        );

        $response
            ->assertStatus(403)
            ->assertJsonFragment(['message' => 'Admin Access Only']);
    }

    public function testCreateFilm_shouldReturn401_whenNotConnected()
    {
        $response = $this->postJson(
            '/api/films',
            ['title' => 'title',
            'release_year' => 2000,
            'length' => 123,
            'description' => 'description',
            'rating' => 'G',
            'language_id' => 1,
            'special_features' => 'Deleted Scenes',
            'image' => '']
        );

        $response
            ->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthenticated.']);
    }

    /**/

    public function testUpdateFilm_shouldReturn200_whenEveryFieldAreFilled()
    {
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization' => 'Bearer ' . $this->adminToken])
                        ->put(
                            '/api/films/1',
                            ['title' => 'testtitle',
                        'release_year' => 2000,
                        'length' => 100,
                        'description' => 'description',
                        'rating' => 'G',
                        'language_id' => 1,
                        'special_features' => 'Deleted Scenes',
                        'image' => '']
                        );

        $response->assertStatus(200)
                ->assertJsonFragment(['title' => 'testtitle']);
    }

    public function testUpdateFilm_shouldReturn403_whenNotAdmin()
    {
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization' => 'Bearer ' . $this->memberToken])
                        ->put(
                            '/api/films/1',
                            ['title' => 'testtitle',
                        'release_year' => 2000,
                        'length' => 100,
                        'description' => 'description',
                        'rating' => 'G',
                        'language_id' => 1,
                        'special_features' => 'Deleted Scenes',
                        'image' => '']
                        );

        $response
            ->assertStatus(403)
            ->assertJsonFragment(['message' => 'Admin Access Only']);
    }

    public function testUpdateFilm_shouldReturn401_whenNotConnected()
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
                        ->put(
                            '/api/films/1',
                            ['title' => 'testtitle',
                        'release_year' => 2000,
                        'length' => 100,
                        'description' => 'description',
                        'rating' => 'G',
                        'language_id' => 1,
                        'special_features' => 'Deleted Scenes',
                        'image' => '']
                        );

        $response
            ->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthenticated.']);
    }

    /**/

    public function testDestroyFilm_shouldReturn200_whenEveryFieldAreFilled()
    {
        $film = $this->createFilm();
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization' => 'Bearer ' . $this->adminToken])
                        ->delete('api/films/' . $film['id']);

        $response->assertStatus(200);
    }

    public function testDestroyFilm_shouldReturn403_whenNotAdmin()
    {
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization' => 'Bearer ' . $this->memberToken])
                        ->delete('api/films/1');

        $response
            ->assertStatus(403)
            ->assertJsonFragment(['message' => 'Admin Access Only']);
    }

    public function testDestroyFilm_shouldReturn401_whenNotConnected()
    {
        $response = $this->deleteJson('api/films/1');

        $response
            ->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthenticated.']);
    }

    /*****************************
    * Critics
    ****************************/

    public function testCreateCritic_shouldReturn201()
    {
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization' => 'Bearer ' . $this->adminToken])->post(
            '/api/critics',
            ['film_id' => 1, 'score' => 2.0]
        );

        $response
            ->assertStatus(201);
    }

    public function testCreateCritic_shouldReturn401_whenNotConnected()
    {
        $response = $this->postJson(
            '/api/critics',
            ['film_id' => 1, 'score' => 2.0]
        );

        $response
            ->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthenticated.']);
    }

    public function testCreateCritic_shouldReturn422_whenNotEveryFieldAreFilled()
    {
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization' => 'Bearer ' . $this->adminToken])->post(
            '/api/critics',
            ['film_id' => 1]
        );


        $response
            ->assertStatus(422)
            ->assertJsonFragment(['message' => 'The given data was invalid.']);
    }


    /*****************************
    * User
    ****************************/
    public function testGetUserInfo_shouldReturnLogin_test1()
    {
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization' => 'Bearer ' . $this->adminToken])->post('/api/user');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['login' => 'test101']);
    }

    public function testGetUserInfo_shouldReturn401_whenNotConnected()
    {
        $response = $this->postJson('/api/user');

        $response
            ->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthenticated.']);
    }

    public function testUpdateUser_shouldReturn201()
    {
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization' => 'Bearer ' . $this->adminToken])->put('/api/user', ['login' => 'test104']);

        $response
            ->assertStatus(201)
            ->assertJsonFragment(['login' => 'test104']);
    }

    public function testUpdateUser_shouldReturn401_whenNotConnected()
    {
        $response = $this->putJson('/api/user');

        $response
            ->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthenticated.']);
    }
}