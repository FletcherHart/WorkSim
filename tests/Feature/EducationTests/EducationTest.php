<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Degree;
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
     * Ensure that a user can see a list of degrees
     *
     * @return void 
     */
    public function test_user_can_see_list_of_degree_programs() 
    {
        $response = Livewire::test('degrees');

        foreach ($this->degrees as $degree)
        {
            $response->assertSee($degree->title)
            ->assertSee($degree->description)
            ->assertSee($degree->cost);
        }
    }
}
