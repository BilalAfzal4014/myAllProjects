<?php

namespace Tests\Unit\Auth;

use App\Components\AppStatusCodes;
use App\Components\Testing\MockHttpRequest;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use MockHttpRequest;

    /** @test */
    public function it_can_login()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_OK,
                'status'    => 'success'
            ],
            'data' => [
                'token' => 'some-test-token'
            ]
        ];

        $expectedEndPoint = route('api.login', 'v1');

        $expectedParams = [
            'email'     => 'john@example.com',
            'password'  => 'john'
        ];

        $mockHttpClient = $this->mockHttpRequest(
            'post',
            $expectedEndPoint,
            $expectedResponse,
            $expectedParams
        );

        $this->assertEquals($expectedResponse, $mockHttpClient->post($expectedEndPoint));
    }

    /** @test */
    public function it_throws_error_if_empty_credentials_are_provided_for_login()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY,
                'status'    => 'error'
            ],
            'error' => [
                'The password field is required'
            ]
        ];

        $expectedEndPoint = route('api.login', 'v1');

        $expectedParams = [
            'email'     => 'john@example.com',
            'password'  => ''
        ];

        $mockHttpClient = $this->mockHttpRequest(
            'post',
            $expectedEndPoint,
            $expectedResponse,
            $expectedParams
        );

        $this->assertEquals($expectedResponse, $mockHttpClient->post($expectedEndPoint));
    }

    /** @test */
    public function it_throws_error_if_invalid_credentials_are_provided_for_login()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY,
                'status'    => 'error'
            ],
            'error' => [
                'email' => 'These credentials do not match our records.'
            ]
        ];

        $expectedEndPoint = route('api.login', 'v1');

        $expectedParams = [
            'email'     => 'john@example.com',
            'password'  => ''
        ];

        $mockHttpClient = $this->mockHttpRequest(
            'post',
            $expectedEndPoint,
            $expectedResponse,
            $expectedParams
        );

        $this->assertEquals($expectedResponse, $mockHttpClient->post($expectedEndPoint));
    }

    /** @test */
    public function it_throws_error_if_a_logged_in_user_again_access_login()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY,
                'status'    => 'error'
            ],
            'error' => [
                'User has already logged in'
            ]
        ];

        $expectedEndPoint = route('api.login', 'v1');

        $expectedParams = [
            'email'     => 'john@example.com',
            'password'  => 'john'
        ];

        $mockHttpClient = $this->mockHttpRequest(
            'post',
            $expectedEndPoint,
            $expectedResponse,
            $expectedParams
        );

        $this->assertEquals($expectedResponse, $mockHttpClient->post($expectedEndPoint));
    }
}
