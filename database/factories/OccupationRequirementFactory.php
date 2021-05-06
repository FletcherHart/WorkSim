<?php

namespace Database\Factories;

use App\Models\OccupationRequirement;
use Illuminate\Database\Eloquent\Factories\Factory;

class OccupationRequirementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OccupationRequirement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'charisma' => $this->faker->numberBetween(0, 255),
            'intelligence' => $this->faker->numberBetween(0, 255),
            'fitness' => $this->faker->numberBetween(0, 255),
        ];
    }
}
