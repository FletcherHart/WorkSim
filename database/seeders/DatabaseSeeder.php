<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $jobs = \App\Models\Occupation::factory(10)->create();
        $degrees = \App\Models\Degree::factory()->count(5)->create();
        $companies = \App\Models\Company::factory(5)->create();

        foreach($jobs as $job) {
            $job->companies()->attach($companies[rand(0, 4)]->id);
            \App\Models\OccupationRequirement::factory(['occupation_id' => $job->id])->create();
        }

        foreach($degrees as $degree) {
            $degree->occupations()->save($jobs[rand(0, 9)]);
        }
    }
}
