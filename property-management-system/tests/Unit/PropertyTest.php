<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;


class PropertyTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_a_property_can_be_created()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $property = Property::create([
            'name' => 'Luxury Apartment',
            'address' => 'Dubai',
            'rent_amount' => 12000,
            'owner_id' => $user->id
        ]);

        $this->assertDatabaseHas('properties', [
            'name' => 'Luxury Apartment',
            'address' => 'Dubai',
            'rent_amount' => 12000,
            'owner_id' => $user->id
        ]);
    }

    public function test_a_property_can_be_updated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $property = Property::factory()->create([
            'owner_id' => $user->id
        ]);

        $property->update([
            'name' => 'Updated Luxury Apartment',
            'address' => 'Dubai Downtown',
            'rent_amount' => 15000
        ]);

        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'name' => 'Updated Luxury Apartment',
            'address' => 'Dubai Downtown',
            'rent_amount' => 15000
        ]);
    }
}
