<?php

namespace Tests\Unit\Segments;

use App\Components\AppStatusCodes;
use App\Components\Testing\MockHttpRequest;
use Tests\TestCase;

class SegmentsResourceTest extends TestCase
{
    use MockHttpRequest;

    /** @test */
    public function it_can_access_segments_index_page()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_OK,
                'status'    => 'success'
            ],
            'data' => [
                [
                    'id' => 1,
                    "name" => "Test Segment",
                    "tags" => "Test,TestSegment",
                    'app_group_id' => 1
                ]
            ]
        ];

        $expectedEndPoint = route('api.home', 'v1');

        $expectedParams = [
            'headers' => [
                'Authorization' => 'Bearer: some-test-token'
            ],
            'json' => [
                'resource' => 'segments',
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
    public function it_can_create_segment()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_OK,
                'status'    => 'success'
            ],
            'data' => [
                [
                    'id' => 1,
                    "name" => "Test Segment",
                    "tags" => "Test,TestSegment",
                    'app_group_id' => 1
                ]
            ]
        ];

        $expectedEndPoint = route('api.home', 'v1');

        $expectedParams = [
            'headers' => [
                'Authorization' => 'Bearer: some-test-token'
            ],
            'json' => [
                'resource'  => 'segments',
                'action'    => 'create',
                'data'      => [
                    'name'      => 'Test Segment',
                    "criteria"  => "(app_id='com.dev.engagement')",
		            "tags"      => "Test,TestSegment"
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
    public function it_can_update_segment()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_OK,
                'status'    => 'success'
            ],
            'data' => [
                [
                    'id' => 1,
                    "name" => "Test Segment",
                    "tags" => "Test,TestSegment",
                    'app_group_id' => 1
                ]
            ]
        ];

        $expectedEndPoint = route('api.home', 'v1');

        $expectedParams = [
            'headers' => [
                'Authorization' => 'Bearer: some-test-token'
            ],
            'json' => [
                'resource'  => 'segments',
                'action'    => 'update',
                'id'        => 1,
                'data'      => [
                    'name'      => 'Test Segment',
                    "criteria"  => "(app_id='com.dev.engagement')",
                    "tags"      => "Test,TestSegment"
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
    public function it_can_show_segment()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_OK,
                'status'    => 'success'
            ],
            'data' => [
                [
                    'id' => 1,
                    "name" => "Test Segment",
                    "tags" => "Test,TestSegment",
                    'app_group_id' => 1
                ]
            ]
        ];

        $expectedEndPoint = route('api.home', 'v1');

        $expectedParams = [
            'headers' => [
                'Authorization' => 'Bearer: some-test-token'
            ],
            'json' => [
                'resource'  => 'segments',
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
    public function it_can_delete_segment()
    {
        $expectedResponse = [
            'meta' => [
                'code'      => AppStatusCodes::HTTP_OK,
                'status'    => 'success'
            ]
        ];

        $expectedEndPoint = route('api.home', 'v1');

        $expectedParams = [
            'headers' => [
                'Authorization' => 'Bearer: some-test-token'
            ],
            'json' => [
                'resource'  => 'segments',
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
}
