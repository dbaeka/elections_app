<?php

namespace Tests\Feature;

use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegionsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @return void
     */
    public function it_returns_a_region_as_a_resource_object()
    {


        $region = Region::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['role:basic']);

        $response = $this->getJson('/api/v1/regions/1', ['accept' => 'application/vnd.api+json', 'content-type' => 'application/vnd.api+json',]);

        $response->assertStatus(200)->assertJson([
            "data" => [
                "id" => '1',
                "type" => "regions",
                "attributes" => [
                    'name' => $region->name,
                    'created_at' => $region->created_at->toJSON(),
                    'updated_at' => $region->updated_at->toJSON(),
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_returns_all_authors_as_a_collection_of_resource_objects()
    {
        $user = User::factory(1)->create()->first();
        $regions = Region::factory(3)->create();
        Sanctum::actingAs($user, ['role:basic']);

        $response = $this->getJson('/api/v1/regions', ['accept' => 'application/vnd.api+json', 'content-type' => 'application/vnd.api+json',]);
        $response->assertStatus(200)->assertJson([
            "data" => [
                [
                    "id" => '1',
                    "type" => "regions",
                    "attributes" => [
                        'name' => $regions[0]->name,
                        'created_at' => $regions[0]->created_at->toJSON(),
                        'updated_at' => $regions[0]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '2',
                    "type" => "regions",
                    "attributes" => [
                        'name' => $regions[1]->name,
                        'created_at' => $regions[1]->created_at->toJSON(),
                        'updated_at' => $regions[1]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '3',
                    "type" => "regions",
                    "attributes" => [
                        'name' => $regions[2]->name,
                        'created_at' => $regions[2]->created_at->toJSON(),
                        'updated_at' => $regions[2]->updated_at->toJSON(),
                    ]
                ]
            ]
        ]);
    }
}
