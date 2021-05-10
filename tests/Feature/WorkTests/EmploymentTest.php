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

class EmploymentTest extends TestCase
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

        $this->company = Company::factory()->create();

        $this->numJobs = 3;
        $numDegrees = 3;

        $this->degrees = Degree::factory()->count($numDegrees)->create();

        $this->occupations = Occupation::factory(
            [
            'company_id' => $this->company->id
            ]
        )->count($this->numJobs)->create();

        foreach ($this->occupations as $occupation) {
            OccupationRequirement::factory(
                [
                'occupation_id' => $occupation->id
                ]
            )->create();
        }

        foreach ($this->degrees as $degree) {
            $degree->occupations()
                ->save($this->occupations[rand(0, $this->numJobs-1)]);
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

        foreach ($this->occupations as $occupation) {
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
        foreach ($this->occupations as $occupation) {
            array_push(
                $degrees, 
                Degree::where('id', $occupation->degree_id)->first()
            );
            array_push(
                $occupation_reqs, 
                OccupationRequirement::where('occupation_id', $occupation->id)
                    ->first()
            );
        }

        //Remove null values from degrees array
        $degrees = array_filter($degrees);

        foreach ($degrees as $degree) {
            $response->assertSee('Degree: ' . $degree->title);
        }
        foreach ($occupation_reqs as $req) {
            $response->assertSee('Charisma: ' . $req->charisma);
            $response->assertSee('Fitness: ' . $req->fitness);
            $response->assertSee('Intelligence: ' . $req->intelligence);
        }
    }

    /**
     * Assert a user visiting employment page does NOT see any taken jobs
     *
     * @return void
     */
    public function test_employment_page_does_not_display_taken_jobs()
    {
        //Create several users
        $num_users = 3;
        User::factory()->count($num_users)->create();

        $taken_jobs = [];

        //Start at 1 as no id can be 0.
        for ($i=1;$i<=$num_users;$i++) {

            $occupation = $this->occupations[rand(1, $this->numJobs-1)];
            
            $user_job = UserOccupation::where(
                [
                ['occupation_id', '=', $occupation->id], 
                ]
            )->first();

            if ($user_job == null) {
                UserOccupation::create(
                    [
                    'occupation_id' => $occupation->id,
                    'user_id' => User::firstWhere('id', $i)->id
                    ]
                );
            } else {
                $user_job->user_id = $i;
                $user_job->save();
            }

            $taken_jobs[] = $occupation;
        }

        //Create user now to prevent player getting a job, 
        //as player job is always displayed in sidebar
        $this->actingAs($user = User::factory()->create());

        $response = Livewire::test('employment');

        foreach ($taken_jobs as $occupation) {
            $response->assertDontSee($occupation->title);
            $response->assertDontSee($occupation->description);
        }
    }

    /**
     * Assert a user can apply to a job & gets relavent Livewire page
     *
     * @return void
     */
    public function test_user_can_apply_to_a_job()
    {
        $this->actingAs($user = User::factory()->create());
        Livewire::test('apply', ['id' => 1])->assertViewIs('livewire.apply');
    }

    /**
     * Assert a user who does not have relevant degree fails application
     *
     * @return void
     */
    public function test_user_without_needed_degree_does_not_get_job()
    {
        //Ensure user qualifies other than degree
        $this->actingAs(
            $user = User::factory(
                [
                'charisma'=>200,
                'fitness'=>200,
                'intelligence'=>200,
                ]
            )->create()
        );

        $occupation = Occupation::where('degree_id', '!=', null)->first();

        $reqs = OccupationRequirement::where('id', $occupation->id)->first();
        $reqs->charisma = 5;
        $reqs->fitness = 5;
        $reqs->intelligence = 5;
        $reqs->save();

        Livewire::test('apply', ['id' => 1])
            ->assertSet('result', false)
            ->assertSee('Oops! It looks like you don\'t have the necessary education.');
        $this->assertDatabaseMissing(
            'user_occupation', 
            ['user_id' => $user->id, 'occupation_id' => $occupation->id]
        );
    }

    
    /**
     * Assert a user who qualifies for an NPC occupation automatically gets it.
     * Also assert that said user is properly notified.
     *
     * @return void
     */
    public function test_qualifying_user_auto_gets_npc_job()
    {
        $this->actingAs(
            $user = User::factory(
                [
                'charisma'=>200,
                'fitness'=>200,
                'intelligence'=>200,
                ]
            )->create()
        );

        //Get & modify first existing occupation,
        //which is guaranteed to exist due to setup()
        $occupation = Occupation::where('id', 1)->first();

        $reqs = OccupationRequirement::where('id', $occupation->id)->first();
        $reqs->charisma = 5;
        $reqs->fitness = 5;
        $reqs->intelligence = 5;
        $reqs->save();

        //Ensure user has needed degree
        if($occupation->degree_id != null)
        {
            UserDegree::create([
                'user_id' => $user->id,
                'degree_id' => $occupation->degree_id
            ]);
        }

        Livewire::test('apply', ['id' => 1])
            ->assertSet('result', true)
            ->assertSee(
                'Congrats! You have been accepted for the position of ' 
                . 
                $occupation->title
            );
        $this->assertDatabaseHas(
            'user_occupation', 
            ['user_id' => $user->id, 'occupation_id' => $occupation->id]
        );
    }

    /**
     * Assert a user with a job can switch to a new job.
     * 
     * @return void
     */
    public function test_user_can_change_jobs()
    {
        $this->actingAs(
            $user = User::factory(
                [
                'charisma'=>200,
                'fitness'=>200,
                'intelligence'=>200,
                ]
            )->create()
        );

        //Get first existing occupation which is guaranteed to exist due to setup()
        $current_job = Occupation::where('id', 1)->first();

        UserOccupation::create(
            [
                'user_id' => $user->id,
                'occupation_id' => $current_job->id
            ]
        );

        //Get & modify second existing occupation, which is guaranteed
        //to exist due to setup()
        $occupation = Occupation::where('id', 2)->first();

        $reqs = OccupationRequirement::where('id', $occupation->id)->first();
        $reqs->charisma = 5;
        $reqs->fitness = 5;
        $reqs->intelligence = 5;
        $reqs->save();

        //Ensure user has needed degree
        if($occupation->degree_id != null)
        {
            UserDegree::create([
                'user_id' => $user->id,
                'degree_id' => $occupation->degree_id
            ]);
        }

        Livewire::test('apply', ['id' => $occupation->id])
            ->assertSet('result', true);
        $this->assertDatabaseHas(
            'user_occupation', 
            ['user_id' => $user->id, 'occupation_id' => $occupation->id]
        );
        $this->assertDatabaseMissing(
            'user_occupation', 
            ['user_id' => $user->id, 'occupation_id' => $current_job->id]
        );
    }

    /**
     * Assert a user cannot take another user's job.
     * 
     * @return void
     */
    public function test_user_cannot_take_another_users_job() 
    {
        $this->actingAs(
            $user = User::factory(
                [
                    'charisma'=>255,
                    'fitness'=>255,
                    'intelligence'=>255,
                ]
            )
            ->create()
        );

        $second_user = User::factory(
            [
                'charisma'=>200,
                'fitness'=>200,
                'intelligence'=>200,
            ]
        )->create();

        //Get first existing occupation which is guaranteed to exist due to setup()
        $occupation = Occupation::where('id', 1)->first();

        UserOccupation::create(
            [
                'user_id' => $second_user->id,
                'occupation_id' => $occupation->id
            ]
        );

        //Ensure user has needed degree
        if($occupation->degree_id != null)
        {
            UserDegree::create([
                'user_id' => $user->id,
                'degree_id' => $occupation->degree_id
            ]);
        }

        Livewire::test('apply', ['id' => $occupation->id])
        ->assertSet('result', false)
        ->assertSee('Oops! It looks like this position is already filled.');

        $this->assertDatabaseHas(
            'user_occupation', 
            ['user_id' => $second_user->id, 'occupation_id' => $occupation->id]
        );
        $this->assertDatabaseMissing(
            'user_occupation', 
            ['user_id' => $user->id, 'occupation_id' => $occupation->id]
        );
    }
}
