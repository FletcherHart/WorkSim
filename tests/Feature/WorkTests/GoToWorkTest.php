<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Degree;
use App\Models\Occupation;
use App\Models\OccupationRequirement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GoToWorkTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Assert non-authenticated user cannot view work page
     *
     * @return void
     */
    public function test_non_auth_user_receives_302_on_work_page()
    {
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
        $this->actingAs($user = User::factory()->create());

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
        $this->actingAs($user = User::factory()->create());

        $response = $this->get('/work');

        $response->assertSee("You are currently unemployed. Please click the button below to look for jobs.");
    }

}
