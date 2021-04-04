<?php

namespace Tests\Feature;

use App\Models\Film;

use Illuminate\Support\Facades\Artisan;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FilmTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp() : void {
        parent::setUp();
        $_GET['keyword'] = null;
        $_GET['minlength'] = null;
        $_GET['maxlength'] = null;
        $_GET['rating'] = null;
        Artisan::call('migrate:rollback');
        Artisan::call('migrate');
        Artisan::call('db:seed');
    }

    public function test_routeFilms()
    {
        $response = $this->get('/api/films');

        $response->assertStatus(200);
    }

    public function test_routeFilm_whenFilmExists()
    {
        $response = $this->get('/api/films/1');

        $response->assertStatus(200);
    }

    public function test_routeFilm_whenFilmDoesntExist()
    {
        $response = $this->get('/api/films/1000');

        $response->assertStatus(404);
    }

    public function test_getFilmCritic_whenFilmHasNoCritics()
    {
        $response = $this->get('/api/films/2/critics');

        $response->assertStatus(204);
    }

    public function test_getFilmCritic_whenFilmHasCritics()
    {
        $response = $this->get('/api/films/1/critics');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['comment'=>'This is a comment.']);
    }

    public function test_getFilmActors()
    {
        $response = $this->get('/api/films/1/actors');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['first_name'=>'CHRISTIAN'])
            ->assertJsonFragment(['last_name'=>'GABLE']);
    }

    public function test_getFilms_whenNoRestrictTitleAndDescription()
    {
        $response = $this->getJson('/api/films');
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['title'=>'ACADEMY DINOSAUR'])
            ->assertJsonFragment(['total'=>100]);   
    }

    public function test_getFilms_whenRestrictTitleAndDescription_andNothingFit_status204()
    {
        $_GET['keyword'] = 'TEST';
        $response = $this->getJson('/api/films');

        $response->assertStatus(204);  
    }

    public function test_getFilms_whenRestrictTitleAndDescription_andOneFits()
    {
        $_GET['keyword'] = 'airport';
        $response = $this->getJson('/api/films');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['title'=>'AIRPORT POLLOCK'])
            ->assertJsonFragment(['total'=>1]);   
    }

    public function test_getFilms_whenRestrictTitleAndDescription_andManyFit()
    {
        $_GET['keyword'] = 'epic';
        $response = $this->getJson('/api/films');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['total'=>6]);   
    }

    public function test_getFilms_whenRestrictMinLength_andNothingFit_status204()
    {
        $_GET['minlength'] = '500';
        $response = $this->getJson('/api/films');

        $response->assertStatus(204);  
    }

    public function test_getFilms_whenRestrictMinLength_andOneFits()
    {
        $_GET['minlength'] = '182';
        $response = $this->getJson('/api/films');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['length'=>182])
            ->assertJsonFragment(['total'=>1]);   
    }

    public function test_getFilms_whenRestrictMinLength_andManyFits()
    {
        $_GET['minlength'] = '177';
        $response = $this->getJson('/api/films');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['length'=>179])
            ->assertJsonFragment(['length'=>180])
            ->assertJsonFragment(['length'=>181])
            ->assertJsonFragment(['length'=>182])
            ->assertJsonFragment(['total'=>5]);   
    }

    public function test_getFilms_whenRestrictMaxLength_andNothingFits_status204()
    {
        $_GET['maxlength'] = '40';
        $response = $this->getJson('/api/films');

        $response->assertStatus(204);
    }

    public function test_getFilms_whenRestrictMaxLength_andOneFits()
    {
        $_GET['maxlength'] = '47';
        $response = $this->getJson('/api/films');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['length'=>46])
            ->assertJsonFragment(['total'=>1]);   
    }

    public function test_getFilms_whenRestrictMaxLength_andManyFits()
    {
        $_GET['maxlength'] = '49';
        $response = $this->getJson('/api/films');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['length'=>46])
            ->assertJsonFragment(['length'=>48])
            ->assertJsonFragment(['total'=>2]);   
    }

    public function test_getFilms_whenRestrictRating_andNothingFits_status204()
    {
        $_GET['rating'] = 'ABC';
        $response = $this->getJson('/api/films');

        $response->assertStatus(204);  
    }

    public function test_getFilms_whenRestrictRating_andManyFits()
    {
        $_GET['rating'] = 'G';
        $response = $this->getJson('/api/films');

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['total'=>25]);   
    }

    public function test_postFilms_status405()
    {
        $response = $this->post('/api/films');

        $response->assertStatus(405);
    }
}
