<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Degree;
use App\Models\Occupation;
use App\Models\OccupationRequirement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire;
use Tests\TestCase;

class EmploymentTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Initialize necessary database data
     *
     * @return void 
     */
    public function setUp():void {
        parent::setUp();

        $this->company = Company::factory()->create();

        $numJobs = 3;
        $numDegrees = 3;

        $this->degrees = Degree::factory()->count($numDegrees)->create();

        $this->occupations = Occupation::factory()->count($numJobs)->create();

        foreach($this->occupations as $occupation) {
            $occupation->companies()->attach($this->company->id);
            OccupationRequirement::factory(['occupation_id' => $occupation->id])->create();
        }

        foreach($this->degrees as $degree) {
            $degree->occupations()->save($this->occupations[rand(0, $numJobs-1)]);
        }
    }

    /**
     * Assert a user visiting employment page sees list of jobs & their companies.
     *
     * @return void
     */
    public function test_employment_page_displays_list_of_jobs()
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->get('/employment');

        foreach($this->occupations as $occupation) {
            $response->assertSee($occupation->title);
            $response->assertSee($occupation->description);
            $response->assertSee($occupation->salary);
            $response->assertSee($this->company->company_name);
        }
    }

    /**
     * Assert displayed jobs also list stat & education requirements
     *
     * @return void
     */
    public function test_employment_page_displays_occupation_requirements()
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->get('/employment');

        $degrees = [];
        $occupation_reqs = [];
        foreach($this->occupations as $occupation) {
            array_push($degrees, Degree::where('id', $occupation->degree_id)->first());
            array_push($occupation_reqs, OccupationRequirement::where('occupation_id', $occupation->id)->first());
        }

        //Remove null values from degrees array
        $degrees = array_filter($degrees);

        foreach($degrees as $degree) {
            $response->assertSee('Degree: ' . $degree->title);
        }
        foreach($occupation_reqs as $req) {
            $response->assertSee('Charisma: ' . $req->charisma);
            $response->assertSee('Fitness: ' . $req->fitness);
            $response->assertSee('Intelligence: ' . $req->intelligence);
        }
    }

    /**
     * Assert a user can apply to a job & gets relavent Livewire page
     *
     * @return void
     */
    public function test_user_can_apply_to_a_job() {
        $this->actingAs($user = User::factory()->create());
        Livewire::test('apply', ['id' => 1])->assertViewIs('livewire.apply');
    }

    
    /**
     * Assert a user who qualifies for an NPC occupation automatically gets it.
     * Also assert that said user is properly notified.
     * @return void
     */
    public function test_qualifying_user_auto_gets_npc_job() {
        $this->actingAs($user = User::factory(['charisma'=>200,'fitness'=>200,'intelligence'=>200,])->create());

        //Get & modify first existing occupation which is guaranteed to exist due to setup()
        $occupation = Occupation::where('id', 1)->first();

        $reqs = OccupationRequirement::where('id', $occupation->id)->first();
        $reqs->charisma = 5;
        $reqs->fitness = 5;
        $reqs->intelligence = 5;
        $reqs->save();

        //$response = $this->get('/apply/' . $occupation->id);
        Livewire::test('apply', ['id' => 1])->assertSet('result', true);
        $this->assertDatabaseHas('user_occupation', ['user_id' => $user->id, 'occupation_id' => $occupation->id]);
    }
}
