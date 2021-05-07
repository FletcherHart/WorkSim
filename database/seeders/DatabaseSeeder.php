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
        $companies = \App\Models\Company::factory(5)->create();
        $jobs = \App\Models\Occupation::factory(10)->create();
        $degrees = \App\Models\Degree::factory()->count(5)->create();
        $stats = ['Intelligence', 'Charisma', 'Fitness'];

        foreach($jobs as $job) {
            $companies[rand(0, 4)]->jobs()->save($job);
                //Get if exists, create new otherwise
                $req = \App\Models\OccupationRequirement::firstOrNew([
                    'occupation_id' => $job->id,
                    'charisma' => rand(0, 255),
                    'intelligence' => rand(0, 255),
                    'fitness' => rand(0, 255),
                ]);
                $req->save();
        }

        foreach($degrees as $degree) {
            $degree->occupations()->save($jobs[rand(0, 9)]);
        }
    }
}
