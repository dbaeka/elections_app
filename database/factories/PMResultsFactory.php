<?php

namespace Database\Factories;

use App\Models\PMResult;
use App\Models\Result;
use Illuminate\Database\Eloquent\Factories\Factory;

class PMResultsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PMResult::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'records' => [1 => 1000, 2 => 4000],
            'user_id' => 1,
            'constituency_id' => 186,
            'station_code' => "HG013131"
        ];
    }
}
