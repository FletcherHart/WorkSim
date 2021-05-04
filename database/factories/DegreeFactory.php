<?php

namespace Database\Factories;

use App\Models\Degree;
use Illuminate\Database\Eloquent\Factories\Factory;

class DegreeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Degree::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->bs(),
            'experience_req' => $this->faker->numberBetween(10, 500),
        ];
    }
}
