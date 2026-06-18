<?php

namespace Database\Factories;

use App\Models\Feature;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Feature>
 */
class FeatureFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name' => $this->faker->sentence(),

            'status' => $this->faker->randomElement(
                array: ['Proposed', 'Planned', 'In Progress', 'Completed']
            ),

            'type' => $this->faker->randomElement(
                array: ['Feature', 'Bugfix', 'Integration']
            ),

            'description' => $this->faker->paragraph(),

            'effort_in_days' => $this->faker->numberBetween(
                int1: 1,
                int2: 300
            ),

            'priority' => $this->faker->numberBetween(
                int1: 1,
                int2: 10
            ),

            'cost' => $this->faker->randomFloat(
                nbMaxDecimals: 2,
                min: 2000,
                max: 200000
            ),

            'target_delivery_date' => $this->faker
                ->optional()
                ->dateTimeBetween(
                    startDate: now(),
                    endDate: now()->addYear()
                ),

            'delivered_at' => null,
        ];
    }
}
