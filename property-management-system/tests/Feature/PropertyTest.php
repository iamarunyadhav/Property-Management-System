<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PropertyTest extends TestCase
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

    public function test_a_property_can_create()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/properties', [
            'name'=>"meenu Luxury Apartments",
            'address'=>'Dubai',
            'rent_amount'=>12000
        ]);
        $response->assertStatus(201);
    }

    public function test_a_property_can_update()
    {
        //createuser->create property with authenticated user ->update with pujson ->check response

        $user = User::factory()->create();
        $this->actingAs($user);

        $property = Property::factory()->create();

        $response = $this->putJson("/api/properties/{$property->id}", [
            'name' => "meenu Luxury Apartments Updated",
            'address' => 'Dubai Updated',
            'rent_amount' => 13000
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'name' => "meenu Luxury Apartments Updated",
            'address' => 'Dubai Updated',
            'rent_amount' => 13000
        ]);
    }


    public function test_a_property_can_delete()
    {
        //create user->create property with authenticated user ->delete with deletejson ->check response
        $user = User::factory()->create();
        $this->actingAs($user);
        $property = Property::factory()->create();
        $response = $this->deleteJson("/api/properties/{$property->id}");

        $response->assertStatus(200);

    }

    //check all possible case of property rent distribution

    // Case 1: One Tenant - Pays full rent
    public function test_one_tenant_pays_full_rent()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $property=Property::factory()->create([
            'owner_id'=>$user->id,
            'rent_amount'=>1000,
        ]);

        $tenant=Tenant::factory()->create([
            'name'=>'imarun',
            'email'=>'imarun@gmail.com',
            'phone_number'=>'0661408018',
            'property_id'=>$property->id,
            'agreement_percentage'=>''
        ]);

        $property->refresh();

        $response = $this->getJson("/api/properties/{$property->id}/rent-distribution");

        // dd($response->json());
        $response->assertStatus(200);

       //check the calculation is correct
       //assertJson is looking the correct order but this one independent with the order
        $response->assertJsonFragment([
            'success' => true,
            'message' => 'Rent distribution calculated.',
            'data' => [
                'total_rent' => 1000, // Ensure correct rent amount
                'property_name' => $property->name,
                'tenants' => [
                    [
                        'id' => $tenant->id,
                        'name' => 'imarun',
                        'rent_share' => 1000, //full rent one tenant
                        'late_fee' => 0
                    ]
                ]
            ]
        ]);
    }


    // Case 2: All tenants have agreement
    public function test_multiple_tenants_with_full_agreement_rent_distributions()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $property=Property::factory()->create([
            'owner_id'=>$user->id,
            'rent_amount'=>1000,
        ]);

        $tenant1=Tenant::factory()->create([
            'name'=>'imarun',
            'email'=>'imarun@gmail.com',
            'phone_number'=>'0661408018',
            'property_id'=>$property->id,
            'agreement_percentage'=>20
        ]);


        $tenant2=Tenant::factory()->create([
            'name'=>'john',
            'email'=>'john@gmail.com',
            'phone_number'=>'0661408019',
            'property_id'=>$property->id,
            'agreement_percentage'=>30
        ]);

        $tenant3=Tenant::factory()->create([
            'name'=>'ramar',
            'email'=>'ramar@gmail.com',
            'phone_number'=>'0661408014',
            'property_id'=>$property->id,
            'agreement_percentage'=>50
        ]);
        $property->refresh();

        $response = $this->getJson("/api/properties/{$property->id}/rent-distribution");

        // dd($response->json());
        $response->assertStatus(200);

       //check the calculation is correct
       //assertJson is looking the correct order but this one independent with the order
        $response->assertJsonFragment([
            'success' => true,
            'message' => 'Rent distribution calculated.',
            'data' => [
                'total_rent' => 1000, // Ensure correct rent amount
                'property_name' => $property->name,
                'tenants' => [
                    [
                        'id' => $tenant3->id,
                        'name' => 'ramar',
                        'rent_share' => 500, //50% of 100
                        'late_fee' => 0
                    ],
                    [
                        'id' => $tenant2->id,
                        'name' => 'john',
                        'rent_share' => 300, // 30% of 1000
                        'late_fee' => 0
                    ],
                    [
                        'id' => $tenant1->id,
                        'name' => 'imarun',
                        'rent_share' => 200, //no aggrement check balance rent
                        'late_fee' => 0
                    ]
                ]
            ]
        ]);
    }


    // Case 3: No Agreements exist
    public function test_multi_tenants_with_no_agreement_rent_distributions()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $property=Property::factory()->create([
            'owner_id'=>$user->id,
            'rent_amount'=>1000,
        ]);

        $tenant1=Tenant::factory()->create([
            'name'=>'imarun',
            'email'=>'imarun@gmail.com',
            'phone_number'=>'0661408018',
            'property_id'=>$property->id,
            'agreement_percentage'=>''
        ]);
        $tenant2=Tenant::factory()->create([
            'name'=>'john',
            'email'=>'john@gmail.com',
            'phone_number'=>'0661408019',
            'property_id'=>$property->id,
            'agreement_percentage'=>''
        ]);

        $property->refresh();

        $response = $this->getJson("/api/properties/{$property->id}/rent-distribution");

        // dd($response->json());
        $response->assertStatus(200);

       //check the calculation is correct
       //assertJson is looking the correct order but this one independent with the order
        $response->assertJsonFragment([
            'success' => true,
            'message' => 'Rent distribution calculated.',
            'data' => [
                'total_rent' => 1000, // Ensure correct rent amount
                'property_name' => $property->name,
                'tenants' => [
                    [
                        'id' => $tenant1->id,
                        'name' => 'imarun',
                        'rent_share' => 500, //divide the rent by total tenant
                        'late_fee' => 0
                    ],
                    [
                        'id' => $tenant2->id,
                        'name' => 'john',
                        'rent_share' => 500, //divide the rent by total tenant
                        'late_fee' => 0
                    ]
                ]
            ]
        ]);

    }

    public function test_a_property_can_have_many_tenants()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $property = Property::factory()->create();

        $tenant1 = Tenant::create([
            'name' => 'John',
            'email'=>'imjohn@gmail.com',
            'phone_number'=>'0561408018',
            'property_id' => $property->id,
            'agreement_percentage' => 50,
        ]);

        $tenant2 = Tenant::create([
            'name' => 'maala',
            'email'=>'maala@gmail.com',
            'phone_number'=>'0561408033',
            'property_id' => $property->id,
            'agreement_percentage' => 50,
        ]);

        $property = Property::with('tenants')->find($property->id);

        // Assert the property has two tenants
        $this->assertCount(2, $property->tenants);
        $this->assertEquals('John', $property->tenants[0]->name);
        $this->assertEquals('maala', $property->tenants[1]->name);
    }


     // Case 4: Agreements exist, but total < 100%  // no needed
    // public function test_tenants_with_partial_agreement()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $property=Property::factory()->create([
    //         'owner_id'=>$user->id,
    //         'rent_amount'=>1000,
    //     ]);

    //     $tenant1=Tenant::factory()->create([
    //         'name'=>'imarun',
    //         'email'=>'imarun@gmail.com',
    //         'phone_number'=>'0661408018',
    //         'property_id'=>$property->id,
    //         'agreement_percentage'=>40
    //     ]);
    //     $tenant2=Tenant::factory()->create([
    //         'name'=>'john',
    //         'email'=>'john@gmail.com',
    //         'phone_number'=>'0661408019',
    //         'property_id'=>$property->id,
    //         'agreement_percentage'=>30
    //     ]);

    //     $property->refresh();

    //     $response = $this->getJson("/api/properties/{$property->id}/rent-distribution");

    //     // dd($response->json());
    //     $response->assertStatus(200);

    //    //check the calculation is correct
    //    //assertJson is looking the correct order but this one independent with the order
    //     $response->assertJsonFragment([
    //         'success' => true,
    //         'message' => 'Rent distribution calculated.',
    //         'data' => [
    //             'total_rent' => 1000, // Ensure correct rent amount
    //             'property_name' => $property->name,
    //             'tenants' => [
    //                 [
    //                     'id' => $tenant1->id,
    //                     'name' => 'imarun',
    //                     'rent_share' => 400, //40% of 1000
    //                     'late_fee' => 0
    //                 ],
    //                 [
    //                     'id' => $tenant2->id,
    //                     'name' => 'john',
    //                     'rent_share' => 300, //30% of 1000
    //                     'late_fee' => 0
    //                 ],
    //                 [
    //                     'id' => 'uncovered',
    //                     'name' => 'Uncovered Rent',
    //                     'rent_share' => 300, //uncovered amount
    //                     'late_fee' => 0
    //                 ]
    //             ]
    //         ]
    //     ]);
    // }

}
