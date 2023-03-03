<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->numerify('honda scoopy ##');
        $slug = Str::slug($request->title, '-'),
        return [
            'title' => $name,
            'slug' => $slug,
            'user_id' => 1,
            'description' => $this->faker->sentence(),
            'weight' => 30,
            'price' => 25000000,
            'stock' => 500,
            'discount' => 0,
        ];
    }
}
