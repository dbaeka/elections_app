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
            'name' => "John Doe",
            'email' => "johndoe@gmail.com",
            'email_verified_at' => now(),
            'phone' => '0503695535',
            'password' => bcrypt('secret'), // password
            'remember_token' => Str::random(10),
        ];
    }
}
