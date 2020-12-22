<?php

namespace Tests\Unit\Auth;

use App\Components\AppStatusCodes;
use App\Components\Testing\MockHttpRequest;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use MockHttpRequest;

    /** @test */
    public function it_can_logout()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_OK,
                'status'    => 'success'
            ],
            'data' => [
                'User has been logged out'
            ]
        ];

        $expectedEndPoint = route('api.logout', 'v1');

        $expectedParams = [
            'headers' => [
                'Authorization' => 'Bearer: some-test-token'
            ]
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
    public function it_throws_error_if_a_logged_out_user_tries_to_logout()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY,
                'status'    => 'error'
            ],
            'error' => [
                'Invalid authorization headers provided.'
            ]
        ];

        $expectedEndPoint = route('api.logout', 'v1');

        $expectedParams = [
            'headers' => [
                'Authorization' => 'Bearer: some-test-token'
            ]
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
