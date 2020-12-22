<?php

namespace Tests\Feature\Segments;

use App\Components\AppStatusCodes;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestResourcesHelper;

class SegmentsResourceTest extends TestCase
{
    use DatabaseTransactions, TestResourcesHelper;

    protected function setUp()
    {
        parent::setUp();

        self::loginCompany();
        self::$itemId = 1;
    }

    /** @test */
    public function it_can_access_segments_index_page()
    {
        $this->postJson(route('api.home', 'v1'), [
            'resource'  => 'segments',
            'action'    => 'get'
        ], [
            'Authorization' => 'Bearer ' . self::$authToken,
        ])->assertStatus(AppStatusCodes::HTTP_OK);
    }

    /** @test */
    public function it_can_create_segment()
    {
        $this->postJson(route('api.home', 'v1'), [
            'resource'  => 'segments',
            'action'    => 'create',
            'data'      => [
                'name'      => 'Test Segment',
                "criteria"  => "(app_id='com.dev.engagement')",
                "tags"      => "Test,TestSegment"
            ]
        ], [
            'Authorization' => 'Bearer ' . self::$authToken,
        ])->assertStatus(AppStatusCodes::HTTP_OK);
    }

    /** @test */
    public function it_can_update_segment()
    {
        $this->postJson(route('api.home', 'v1'), [
            'resource'  => 'segments',
            'action'    => 'update',
            'id'        => 1,
            'data'      => [
                'name'      => 'Test Segment',
                "criteria"  => "(app_id='com.dev.engagement')",
                "tags"      => "Test,TestSegment"
            ]
        ], [
            'Authorization' => 'Bearer ' . self::$authToken,
        ])->assertStatus(AppStatusCodes::HTTP_OK);
    }

    /** @test */
    public function it_can_show_segment()
    {
        $this->postJson(route('api.home', 'v1'), [
            'resource'  => 'segments',
            'action'    => 'get',
            'id'        => self::$itemId,
        ], [
            'Authorization' => 'Bearer ' . self::$authToken,
        ])->assertStatus(AppStatusCodes::HTTP_OK);
    }

    /** @test */
    public function it_can_delete_segment()
    {
        $this->postJson(route('api.home', 'v1'), [
            'resource'  => 'segments',
            'action'    => 'remove',
            'id'        => self::$itemId,
        ], [
            'Authorization' => 'Bearer ' . self::$authToken,
        ])->assertStatus(AppStatusCodes::HTTP_OK);
    }
}
