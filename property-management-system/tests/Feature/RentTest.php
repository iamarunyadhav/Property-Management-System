<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RentTest extends TestCase
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

    public function test_rent_distribution_calculates_rent_correctly()
{
    // Create a property and tenant with rent details
    $user=User::factory()->create();
    $this->actingAs($user);

    $property = Property::factory()->create(['rent_amount' => 10000]);
    $tenant = Tenant::factory()->create([
            "name"=>"tenant1",
            "email"=>"teanat1@gmail.com",
            "phone_number"=>"0561408018",
            "property_id"=>$property->id,
            "agreement_percentage"=>''
    ]);



    // Call the API
    $response = $this->getJson("/api/tenants/{$tenant->id}/rent");
    // Assert the response status and rent calculation
    $response->assertStatus(200)
             ->assertJson([
                 'success' => true,
                 'data' => [
                     ['id'=>$tenant->id ,'name' => $tenant->name, 'rent_share' => 10000 ,"late_fee" => 0]
                 ]
             ]);
}
}
