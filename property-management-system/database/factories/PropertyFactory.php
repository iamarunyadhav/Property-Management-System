<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model=Property::class;

    public function definition(): array
    {
        return [
          'name'=>$this->faker->word.' Property',
          'address'=>$this->faker->address,
          'rent_amount' => round($this->faker->numberBetween(500, 1000), -2),
          'owner_id'=> User::inRandomOrder()->first()->id,
        ];
    }
}
