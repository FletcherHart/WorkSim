<?php

namespace Database\Factories;

use App\Models\Occupation;
use Illuminate\Database\Eloquent\Factories\Factory;

class OccupationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Occupation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $stats = ['charisma', 'fitness', 'intelligence'];
        return [
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->bs(),
            'salary' => $this->faker->numberBetween(100, 5000),
            'bonus_stat' => $stats[rand(0,2)],
        ];
    }
}
