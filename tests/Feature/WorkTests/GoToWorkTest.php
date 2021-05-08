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

}
