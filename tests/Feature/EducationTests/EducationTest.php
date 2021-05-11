<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Degree;
use App\Models\DegreeProgress;
use App\Models\Occupation;
use App\Models\OccupationRequirement;
use App\Models\User;
use App\Models\UserDegree;
use App\Models\UserOccupation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire;
use Tests\TestCase;

class EducationTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Initialize necessary database data
     *
     * @return void 
     */
    public function setUp():void
    {
        parent::setUp();

        $this->actingAs($this->user = User::factory()->create());

        $this->num_degrees = 5;
        $this->degrees = Degree::factory()->count($this->num_degrees)->create();
    }

    /**
     * Ensure that a user can study,
     * which should increase progress
     * toward degree relative to intelligence 
     * 
     * @return void 
     */
    public function test_user_can_increase_progress_toward_degree() 
    {
        $current_progress = 10;

        $degree_progress = DegreeProgress::create(
            [
                'user_id' => $this->user->id,
                'degree_id' => $this->degrees[rand(0, $this->num_degrees - 1)]->id,
                'progress' => $current_progress
            ]
        );

        //Set degree cost to 0 and user money to +1 cost 
        //so user always has enough to pass
        //Also set progress_needed to out of possible range
        $degree = Degree::where('id', $degree_progress->degree_id)->first();
        $degree->cost = 0;
        $degree->progress_needed = 10000;
        $degree->save();
        $this->user->money = $degree->cost + 1;
        $this->user->save();

        $expected_progress = $current_progress + round(1 + ($this->user->intelligence/5));

        $response = Livewire::test('study')
            ->call('makeProgress', $degree_progress->id);

        $this->assertDatabaseHas(
            'degree_progress',
            [
                'user_id' => $this->user->id,
                'progress' => $expected_progress
            ]
        );
    }

    /**
     * Ensure that studying costs
     * the user 1 unit of energy
     * and the cost of the degree 
     * 
     * @return void 
     */
    public function test_studying_costs_energy_and_money() 
    {
  
        $current_progress = 10;
        $current_money = 1000;
        $current_energy = 20;

        $this->user->money = $current_money;
        $this->user->current_energy = $current_energy;
        $this->user->save();

        $degree = $this->degrees[rand(0, $this->num_degrees - 1)];

        $expected_money = $current_money - $degree->cost;

        $degree_progress = DegreeProgress::create(
            [
                'user_id' => $this->user->id,
                'degree_id' => $degree->id,
                'progress' => $current_progress
            ]
        );

        $response = Livewire::test('study')
            ->call('makeProgress', $degree_progress->id)
            ->assertEmitted('updateSidebar');

        $this->assertDatabaseHas(
            'users',
            [
                'id' => $this->user->id,
                'current_energy' => $current_energy-1,
                'money' => $expected_money
            ]
        );
    }

    /**
     * Ensure that user without enough energy
     * does not increase progress
     * 
     * @return void 
     */
    public function test_studying_denied_if_not_enough_energy() 
    {
  
        $current_progress = 10;
        $current_money = 1000;
        $current_energy = 0;

        $this->user->money = $current_money;
        $this->user->current_energy = $current_energy;
        $this->user->save();

        $degree = $this->degrees[rand(0, $this->num_degrees - 1)];

        $degree_progress = DegreeProgress::create(
            [
                'user_id' => $this->user->id,
                'degree_id' => $degree->id,
                'progress' => $current_progress
            ]
        );

        $response = Livewire::test('study')
            ->call('makeProgress', $degree_progress->id);

        //Assert user is not changed
        $this->assertDatabaseHas(
            'users',
            [
                'id' => $this->user->id,
                'current_energy' => $current_energy,
                'money' => $current_money
            ]
        );

        //Assert no progress made
        $this->assertDatabaseHas(
            'degree_progress',
            [
                'user_id' => $this->user->id,
                'progress' => $current_progress
            ]
        );

        $response
        ->assertSee('Oops! It looks like you don\'t have enough energy');
    }

    /**
     * Ensure that user without enough money
     * does not increase progress
     * 
     * @return void 
     */
    public function test_studying_denied_if_not_enough_money() 
    {
  
        $current_progress = 10;
        $current_money = 0;
        $current_energy = 20;

        $this->user->money = $current_money;
        $this->user->current_energy = $current_energy;
        $this->user->save();

        $degree = $this->degrees[rand(0, $this->num_degrees - 1)];

        $degree_progress = DegreeProgress::create(
            [
                'user_id' => $this->user->id,
                'degree_id' => $degree->id,
                'progress' => $current_progress
            ]
        );

        $response = Livewire::test('study')
            ->call('makeProgress', $degree_progress->id);

        //Assert user is not changed
        $this->assertDatabaseHas(
            'users',
            [
                'id' => $this->user->id,
                'current_energy' => $current_energy,
                'money' => $current_money
            ]
        );

        //Assert no progress made
        $this->assertDatabaseHas(
            'degree_progress',
            [
                'user_id' => $this->user->id,
                'progress' => $current_progress
            ]
        );

        $response
        ->assertSee('Oops! It looks like you don\'t have enough money');
    }

    /**
     * Ensure that when progress exceeds needed_progress
     * UserDegrees & DegreeProgress are updated appropriatly
     * 
     * @return void 
     */
    public function test_matching_or_exceeding_progress_threshold_gives_user_degree() 
    {
  
        $current_progress = 10;
        $current_money = 1000;
        $current_energy = 20;

        $this->user->money = $current_money;
        $this->user->current_energy = $current_energy;
        $this->user->save();

        $degree = $this->degrees[rand(0, $this->num_degrees - 1)];
        $degree->progress_needed = 10;
        $degree->save();

        $degree_progress = DegreeProgress::create(
            [
                'user_id' => $this->user->id,
                'degree_id' => $degree->id,
                'progress' => $current_progress
            ]
        );

        $response = Livewire::test('study')
            ->call('makeProgress', $degree_progress->id);

        
        $this->assertDatabaseHas(
            'user_degrees',
            [
                'user_id' => $this->user->id,
                'degree_id' => $degree->id
            ]
        );

        $this->assertDatabaseMissing(
            'degree_progress',
            [
                'user_id' => $this->user->id,
                'degree_id' => $degree->id
            ]
        );

        $response
        ->assertSee('Completed Degrees');
    }
}
