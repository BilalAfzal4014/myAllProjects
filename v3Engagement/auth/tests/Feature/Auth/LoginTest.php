<?php

namespace Tests\Feature\Auth;

use App\Components\AppStatusCodes;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_login()
    {
        $request = $this->postJson('/api/v1/login', [
            'email' => 'company@engagement.com',
            'password' => '123456'
        ]);
        $response = \GuzzleHttp\json_decode($request->getContent(), true);

        $request->assertStatus(AppStatusCodes::HTTP_OK);
        $this->assertNotEmpty($response['data']['token']);
    }

    /** @test */
    public function it_throws_error_if_empty_credentials_are_provided_for_login()
    {
        $request = $this->postJson('/api/v1/login', [
            'email' => 'company@engagement.com',
            'password' => ''
        ]);
        $response = \GuzzleHttp\json_decode($request->getContent(), true);

        $request->assertStatus(AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertTrue(
            in_array('The password field is required.', $response['error'])
        );
    }

    /** @test */
    public function it_throws_error_if_invalid_credentials_are_provided_for_login()
    {
        $request = $this->postJson('/api/v1/login', [
            'email' => 'company@engagement.com',
            'password' => '1234567'
        ]);
        $response = \GuzzleHttp\json_decode($request->getContent(), true);

        $request->assertStatus(AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertTrue(
            in_array('These credentials do not match our records.', $response['error'])
        );
    }
}
