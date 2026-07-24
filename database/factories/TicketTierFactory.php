<?php

namespace Database\Factories;

use App\Models\TicketTier;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketTier>
 */
class TicketTierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = TicketTier::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name' => $this->faker->unique()->words(2, true),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'quantity' => $this->faker->numberBetween(1, 500),
            'sales_channels' => null,
            'is_published' => false,
            'is_active' => true,
        ];
    }
}
