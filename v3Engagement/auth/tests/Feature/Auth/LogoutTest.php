<?php

namespace Tests\Feature\Auth;

use App\Components\AppStatusCodes;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestResourcesHelper;

class LogoutTest extends TestCase
{
    use DatabaseTransactions, TestResourcesHelper;

    protected function setUp()
    {
        parent::setUp();

        self::loginCompany();
    }

    /** @test */
    public function it_can_logout()
    {
        $this->postJson('/api/v1/logout', [], [
            'Authorization' => 'Bearer ' . self::$authToken,
        ])->assertStatus(AppStatusCodes::HTTP_OK);
    }

    /** @test */
    public function it_throws_error_if_a_invalid_access_token_is_provided_for_logout()
    {
        $this->postJson('/api/v1/logout', [], [
            'Authorization' => 'Bearer somedummytoken',
        ])->assertStatus(AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_throws_error_if_a_logged_out_user_tries_to_logout()
    {
        self::$user->tokens()->update(['revoked' => true]);

        $this->postJson('/api/v1/logout', [], [
            'Authorization' => 'Bearer ' . self::$authToken,
        ])->assertStatus(AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
