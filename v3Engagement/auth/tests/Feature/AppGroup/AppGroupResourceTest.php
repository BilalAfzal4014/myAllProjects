<?php

namespace Tests\Feature\AppGroup;

use App\Components\AppStatusCodes;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestResourcesHelper;

class AppGroupResourceTest extends TestCase
{
    use DatabaseTransactions, TestResourcesHelper;

    protected function setUp()
    {
        parent::setUp();

        self::loginCompany();
        self::$itemId = 1;
    }

    /** @test */
    public function it_can_access_app_groups_index_page()
    {
        $this->postJson(route('api.home', 'v1'), [
            'resource'  => 'groups',
            'action'    => 'get'
        ], [
            'Authorization' => 'Bearer ' . self::$authToken,
        ])->assertStatus(AppStatusCodes::HTTP_OK);
    }

    /** @test */
    public function it_can_create_app_group()
    {
        $this->postJson(route('api.home', 'v1'), [
            'resource'  => 'groups',
            'action'    => 'create',
            'data'      => [
                'name'      => 'some name',
                'default'   => false,
                'current'   => false,
            ]
        ], [
            'Authorization' => 'Bearer ' . self::$authToken,
        ])->assertStatus(AppStatusCodes::HTTP_OK);
    }

    /** @test */
    public function it_can_update_app_group()
    {
        $this->postJson(route('api.home', 'v1'), [
            'resource'  => 'groups',
            'action'    => 'update',
            'id'        => self::$itemId,
            'data'      => [
                'name'      => 'some name',
                'default'   => false,
                'current'   => false,
            ]
        ], [
            'Authorization' => 'Bearer ' . self::$authToken,
        ])->assertStatus(AppStatusCodes::HTTP_OK);
    }

    /** @test */
    public function it_can_show_app_group()
    {
        $this->postJson(route('api.home', 'v1'), [
            'resource'  => 'groups',
            'action'    => 'get',
            'id'        => self::$itemId,
        ], [
            'Authorization' => 'Bearer ' . self::$authToken,
        ])->assertStatus(AppStatusCodes::HTTP_OK);
    }

    /** @test */
    public function it_can_delete_app_group()
    {
        $this->postJson(route('api.home', 'v1'), [
            'resource'  => 'groups',
            'action'    => 'remove',
            'id'        => self::$itemId,
        ], [
            'Authorization' => 'Bearer ' . self::$authToken,
        ])->assertStatus(AppStatusCodes::HTTP_OK);
    }

    /** @test */
    public function it_can_set_current_app_group()
    {
        $this->postJson(route('api.home', 'v1'), [
            'resource'  => 'groups',
            'method'    => 'current',
            'action'    => 'update',
            'id'        => self::$itemId,
        ], [
            'Authorization' => 'Bearer ' . self::$authToken,
        ])->assertStatus(AppStatusCodes::HTTP_OK);
    }
}
