<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model=Tenant::class;
    public function definition(): array
    {
        return [
            'name'=>$this->faker->name,
            'email'=>$this->faker->unique()->safeEmail,
            'phone_number'=>$this->faker->phoneNumber,
            'property_id' => Property::inRandomOrder()->first()->id,
            'agreement_percentage' => $this->faker->optional()->randomFloat(2, 0, 100),
        ];
    }
}
