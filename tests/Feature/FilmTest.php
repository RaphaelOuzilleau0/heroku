<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FilmTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /*****************************
     * Index
     ****************************/

    public function testGetFilms_shouldReturnTitleAli_Forever()
    {
        $response = $this->getJson('/api/films');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['title' => 'ALI FOREVER']);
    }

    /*****************************
     * Show
     ****************************/
    
    public function testGetFilmsWithId1_shouldReturnTitleAcademy_Dinosaur()
    {
        $response = $this->getJson('/api/films/1');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['title' => 'ACADEMY DINOSAUR']);
    }
    
    public function testGetFilms_withInvalidId_shouldReturnCode404()
    {
        $response = $this->getJson('/api/films/400');

        $response
            ->assertStatus(404);
    }

    /*****************************
     * Search
     ****************************/

    public function testGetFilmsWithKeywordAcademy_inTitle_shouldReturnTitleAcademy_Dinosaur()
    {
        $response = $this->getJson('/api/films?keyword=ACADEMY');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['title' => 'ACADEMY DINOSAUR'])
            ->assertJsonFragment(['per_page' => 20]);
    }

    public function testGetFilmsWithKeywordFeminist_inDescription_shouldReturnTitleAcademy_Dinosaur()
    {
        $response = $this->getJson('/api/films?keyword=Feminist');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['title' => 'ACADEMY DINOSAUR'])
            ->assertJsonFragment(['per_page' => 20]);
    }

    public function testGetFilmsWithRatingPG_shouldReturnTitleAcademy_Dinosaur()
    {
        $response = $this->getJson('/api/films?rating=PG');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['title' => 'ACADEMY DINOSAUR'])
            ->assertJsonFragment(['per_page' => 20]);
    }

    public function testGetFilmsWithMinLength20_shouldReturnTitleAcademy_Dinosaur()
    {
        $response = $this->getJson('/api/films?minLength=20');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['title' => 'ACADEMY DINOSAUR'])
            ->assertJsonFragment(['per_page' => 20]);
    }

    public function testGetFilmsWithMaxLength90_shouldReturnTitleAcademy_Dinosaur()
    {
        $response = $this->getJson('/api/films?maxLength=90');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['title' => 'ACADEMY DINOSAUR'])
            ->assertJsonFragment(['per_page' => 20]);
    }

    public function testGetFilmsWithNoParam_shouldReturnTitleAcademy_Dinosaur()
    {
        $response = $this->getJson('/api/films');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['title' => 'ACADEMY DINOSAUR']);
    }

    public function testGetFilmsWithNoParam_shouldReturn20PerPages()
    {
        $response = $this->getJson('/api/films');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['per_page' => 20]);
    }

    /*****************************
     * ShowActors
     ****************************/

    // public function testGetActors_withIdOne_shouldReturnPenelopeGuiness()
    // {
    //     $response = $this->getJson('/api/films/1/actors');

    //     $response
    //         ->assertStatus(200)
    //         ->assertJsonFragment(['last_name' => 'GUINESS'])
    //         ->assertJsonFragment(['first_name' => 'PENELOPE']);
    // }

    // public function testGetActors_withInvalidId_shouldReturnCode404()
    // {
    //     $response = $this->getJson('/api/films/400/actors');

    //     $response
    //         ->assertStatus(404);
    // }

    /*****************************
     * ShowCritics
     ****************************/

    public function testGetCritics_withIdOne_shouldReturnScoreOf10()
    {
        $response = $this->getJson('/api/films/1/critics');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['score' => '10.0']);
    }

    public function testGetCritics_withInvalidId_shouldReturnCode404()
    {
        $response = $this->getJson('/api/films/400/critics');

        $response
            ->assertStatus(404);
    }
}