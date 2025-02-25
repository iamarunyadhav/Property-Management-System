<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TenantTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_a_tenant_can_create()
    {
       $user=User::factory()->create([]);
       $this->actingAs($user);

       $Property=Property::factory()->create();


       $Property->refresh();

       $response = $this->postJson("/api/tenants",[
            "name"=>"tenant1",
            "email"=>"teanat1@gmail.com",
            "phone_number"=>"0561408018",
            "property_id"=>$Property->id,
            "agreement_percentage"=>"50"
       ]);
       $response->assertStatus(201);
    }

    public function test_a_tenant_can_delete()
    {
       $user=User::factory()->create([]);
       $this->actingAs($user);

       $property=Property::factory()->create();
       $tenant=Tenant::factory()->create();

       $property->refresh();

       $response = $this->deleteJson("/api/tenants/{$tenant->id}");
    //    dd($response);
       $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => []
           ]);
    }
}
