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
        $stats = ['Intelligence', 'Charisma', 'Fitness'];

        foreach($jobs as $job) {
            $job->companies()->attach($companies[rand(0, 4)]->id);
            for($i = 0; $i<rand(0, 3); $i++) {
                //Get if exists, create new otherwise
                $req = \App\Models\OccupationRequirement::firstOrNew([
                    'occupation_id' => $job->id, 
                    'stat' => $stats[array_rand($stats, 1)],
                ]);
                //Technically might overwrite stat_req but it doesn't matter
                $req->stat_req = rand(1, 255);
                $req->save();
            }
        }

        foreach($degrees as $degree) {
            $degree->occupations()->save($jobs[rand(0, 9)]);
        }
    }
}
