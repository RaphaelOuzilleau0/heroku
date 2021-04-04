<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'login' => 'login',
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password', // password
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'role_id' => '2',
        ];
    }
}
