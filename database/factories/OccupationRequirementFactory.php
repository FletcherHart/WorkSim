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
            'stat' => rand_array(['none', 'intelligence', 'charisma', 'fitness'], 1),
            'stat_req' => $this->$this->faker->numberBetween(1, 255),
        ];
    }
}
