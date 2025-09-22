<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(2, true);

        return [
            'name' => $name,
            'description' => $this->faker->sentence,
            'slug' => \Illuminate\Support\Str::slug($name),
            'parent_id' => null,
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
        ];
    }

    public function withTranslations(): static
    {
        return $this->state(function (array $attributes) {
            $name = $this->faker->words(2, true);
            return [
                'name' => [
                    'en' => $name,
                    'fr' => $name . ' (FR)',
                    'ar' => $name . ' (AR)',
                ],
                'description' => [
                    'en' => $this->faker->sentence,
                    'fr' => $this->faker->sentence . ' (FR)',
                    'ar' => $this->faker->sentence . ' (AR)',
                ],
            ];
        });
    }
}
