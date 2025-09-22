<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company;

        return [
            'name' => [
                'fr' => $name,
                'en' => $name,
                'ar' => $name,
            ],
            'description' => [
                'fr' => $this->faker->text(200),
                'en' => $this->faker->text(200),
                'ar' => $this->faker->text(200),
            ],
            'logo_path' => null,
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
