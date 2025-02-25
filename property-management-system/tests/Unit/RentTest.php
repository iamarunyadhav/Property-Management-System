<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Models\Tenant;
use App\Services\RentService;
use PHPUnit\Framework\TestCase;

class RentTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }


    //     public function test_rent_distribution_for_multiple_tenants()
    // {
    //     $property = Property::factory()->create(['rent_amount' => 1000]);

    //     $tenant1 = Tenant::factory()->create([
    //         'property_id' => $property->id,
    //         'agreement_percentage' => 40
    //     ]);

    //     $tenant2 = Tenant::factory()->create([
    //         'property_id' => $property->id,
    //         'agreement_percentage' => 60
    //     ]);

    //     $rentDistribution = RentService::calculateRentDistribution($property);
    //     // dd($rentDistribution);

    //     $this->assertEquals(400, $rentDistribution['tenants'][0]['rent_share']); // 40% of 1000
    //     $this->assertEquals(600, $rentDistribution['tenants'][1]['rent_share']); // 60% of 1000
    // }

}
