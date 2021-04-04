<?php

namespace Database\Factories;

use Faker\Generator as Faker;
use App\Models\Film;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FilmFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Film::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => 'title',
            'description' =>'description',
            'release_year' => '1990',
            'length' => '100',
            'rating' => 'G',
            'language_id' => '0',
            'special_features' => 'Rien',
            'image' => 'img.png',
            'created_at' => '2000-01-01',
        ];
    }
}
