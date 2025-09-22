<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Brand;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        $price = $this->faker->randomFloat(2, 10, 1000);
        $quantity = $this->faker->numberBetween(0, 100);

        return [
            'name' => [
                'fr' => $name,
                'en' => $name,
                'ar' => $name,
            ],
            'description' => [
                'fr' => $this->faker->paragraph,
                'en' => $this->faker->paragraph,
                'ar' => $this->faker->paragraph,
            ],
            'short_description' => [
                'fr' => $this->faker->sentence,
                'en' => $this->faker->sentence,
                'ar' => $this->faker->sentence,
            ],
            'slug' => \Illuminate\Support\Str::slug($name),
            'sku' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'barcode' => $this->faker->ean13(),
            'price' => $price,
            'compare_price' => $this->faker->boolean(30) ? $price * 1.2 : null,
            'cost_price' => $price * 0.6,
            'stock_quantity' => $quantity,
            'track_quantity' => $this->faker->boolean(90),
            'quantity' => $quantity,
            'allow_backorder' => $this->faker->boolean(20),
            'weight' => $this->faker->randomFloat(2, 0.1, 10),
            'width' => $this->faker->randomFloat(2, 1, 50),
            'height' => $this->faker->randomFloat(2, 1, 50),
            'length' => $this->faker->randomFloat(2, 1, 50),
            'brand_id' => Brand::factory(),
            'is_active' => true,
            'is_featured' => $this->faker->boolean(20),
            'is_digital' => $this->faker->boolean(10),
            'views_count' => $this->faker->numberBetween(0, 1000),
            'sales_count' => $this->faker->numberBetween(0, 100),
        ];
    }
}
