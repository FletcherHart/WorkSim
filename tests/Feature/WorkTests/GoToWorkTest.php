<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Degree;
use App\Models\Occupation;
use App\Models\OccupationRequirement;
use App\Models\User;
use App\Models\UserOccupation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire;
use Tests\TestCase;

class GoToWorkTest extends TestCase
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
        $this->company = Company::factory()->create();
        $this->occupation = Occupation::factory(
            [
                'company_id' => $this->company->id
            ]
        )->create();

        UserOccupation::create(
            [
                'user_id' => $this->user->id,
                'occupation_id' => $this->occupation->id
            ]
        );
    }

    /**
     * Assert non-authenticated user cannot view work page
     *
     * @return void
     */
    public function test_non_auth_user_receives_302_on_work_page()
    {
        //Used to undo actingAs in setup
        auth()->logout();

        $response = $this->get('/work');

        $response->assertStatus(302);
    }

    /**
     * Assert authenticated user CAN view work page
     *
     * @return void
     */
    public function test_auth_user_can_view_work_page()
    {
        $response = $this->get('/work');

        $response->assertStatus(200);
    }

    /**
     * Assert unemployed user sees "You are currently unemployed. Please click the button below to look for jobs."
     *
     * @return void
     */
    public function test_unemployed_user_sees_notice()
    {
        UserOccupation::where('user_id', $this->user->id)->first()->delete();
        $response = $this->get('/work');

        $response->assertSee("You are currently unemployed. Please click the button below to look for jobs.");
    }

        /**
     * Assert employed user can work
     *
     * @return void
     */
    public function test_employed_user_can_work()
    {
        $response = Livewire::test('work')
            ->call('doWork');

        $response->assertStatus(200);
    }

    /**
     * Assert that doing work increases users money by ammount equal to occupation salary
     *
     * @return void
     */
    public function test_working_earns_money()
    {
        $initial_money = 500;
        $this->user->money = $initial_money;
        $this->user->save();

        $response = Livewire::test('work')
            ->call('doWork');

        $this->assertDatabaseHas(
            'users', 
            [
                'id' => $this->user->id, 
                'money' => ($initial_money + $this->occupation->salary)
            ]
        );
    }

    /**
     * Assert that doing work increases company money using formula
     * For this test occupation should not have degree
     * @return void
     */
    public function test_working_earns_company_money_no_degree_needed()
    {
        $initial_money = 500;
        $this->company->money = $initial_money;
        $this->company->save();
        $this->occupation->degree_id = null;
        $this->occupation->save();

        $response = Livewire::test('work')
            ->call('doWork');

        $formula_result = 0;

        if ($this->occupation->bonus_stat == "charisma") 
        {
            $formula_result = $initial_money + (200 - $this->occupation->salary + $this->user->charisma*2 + $this->user->intelligence + $this->user->fitness);
        }
        else if ($this->occupation->bonus_stat == "intelligence")
        {
            $formula_result = $initial_money + (200 - $this->occupation->salary + $this->user->charisma + $this->user->intelligence*2 + $this->user->fitness);
        }
        else if ($this->occupation->bonus_stat == "fitness")
        {
            $formula_result = $initial_money + (200 - $this->occupation->salary + $this->user->charisma + $this->user->intelligence + $this->user->fitness*2);
        }

        $this->assertDatabaseHas(
            'companies', 
            [
                'id' => $this->user->id, 
                'money' => $formula_result
            ]
        );
    }

}
