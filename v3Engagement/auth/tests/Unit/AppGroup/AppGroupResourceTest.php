<?php

namespace Tests\Unit\AppGroup;

use App\Components\AppStatusCodes;
use App\Components\Testing\MockHttpRequest;
use Tests\TestCase;

class AppGroupResourceTest extends TestCase
{
    use MockHttpRequest;

    /** @test */
    public function it_can_access_app_groups_index_page()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_OK,
                'status'    => 'success'
            ],
            'data' => [
                [
                    'id' => 1,
                    'code' => 'some-code',
                    'name' => 'some-name',
                    'company_id' => 2
                ]
            ]
        ];

        $expectedEndPoint = route('api.home', 'v1');

        $expectedParams = [
            'headers' => [
                'Authorization' => 'Bearer: some-test-token'
            ],
            'json' => [
                'resource' => 'groups',
                'action' => 'get'
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
    public function it_can_create_app_group()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_OK,
                'status'    => 'success'
            ],
            'data' => [
                [
                    'id' => 1,
                    'code' => 'some-code',
                    'name' => 'some-name',
                    'company_id' => 2
                ]
            ]
        ];

        $expectedEndPoint = route('api.home', 'v1');

        $expectedParams = [
            'headers' => [
                'Authorization' => 'Bearer: some-test-token'
            ],
            'json' => [
                'resource'  => 'groups',
                'action'    => 'create',
                'data'      => [
                    'name'      => 'some name',
                    'default'   => false,
                    'current'   => false,
                ]
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
    public function it_can_update_app_group()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_OK,
                'status'    => 'success'
            ],
            'data' => [
                [
                    'id' => 1,
                    'code' => 'some-code',
                    'name' => 'some-name',
                    'company_id' => 2
                ]
            ]
        ];

        $expectedEndPoint = route('api.home', 'v1');

        $expectedParams = [
            'headers' => [
                'Authorization' => 'Bearer: some-test-token'
            ],
            'json' => [
                'resource'  => 'groups',
                'action'    => 'update',
                'id'        => 1,
                'data'      => [
                    'name'      => 'some name',
                    'default'   => false,
                    'current'   => false,
                ]
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
    public function it_can_show_app_group()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_OK,
                'status'    => 'success'
            ],
            'data' => [
                [
                    'id' => 1,
                    'code' => 'some-code',
                    'name' => 'some-name',
                    'company_id' => 2
                ]
            ]
        ];

        $expectedEndPoint = route('api.home', 'v1');

        $expectedParams = [
            'headers' => [
                'Authorization' => 'Bearer: some-test-token'
            ],
            'json' => [
                'resource'  => 'groups',
                'action'    => 'get',
                'id'        => 1,
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
    public function it_can_delete_app_group()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_OK,
                'status'    => 'success'
            ],
        ];

        $expectedEndPoint = route('api.home', 'v1');

        $expectedParams = [
            'headers' => [
                'Authorization' => 'Bearer: some-test-token'
            ],
            'json' => [
                'resource'  => 'groups',
                'action'    => 'remove',
                'id'        => 1,
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
    public function it_can_set_current_app_group()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_OK,
                'status'    => 'success'
            ],
        ];

        $expectedEndPoint = route('api.home', 'v1');

        $expectedParams = [
            'headers' => [
                'Authorization' => 'Bearer: some-test-token'
            ],
            'json' => [
                'resource'  => 'groups',
                'method'    => 'current',
                'action'    => 'update',
                'id'        => 1,
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
