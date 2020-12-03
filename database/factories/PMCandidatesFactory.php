<?php

namespace Database\Factories;

use App\Models\PMCandidate;
use Illuminate\Database\Eloquent\Factories\Factory;

class PMCandidatesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PMCandidate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            "name" => "Delmwin Baeka",
            "party_id" => 1,
        ];
    }
}
