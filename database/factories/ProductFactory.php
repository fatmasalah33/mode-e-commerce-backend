<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition()
    {
        return [
            "name" => $this->faker->randomElement(['t-shirt', 'shirt', 'bag ','lenovo-lap','dell-lap','shoes','dress']),
            "description" => $this->faker->randomElement(['good item', 'Not good', 'may be good ']),
            "brand" => $this->faker->randomElement(['h&m', 'Zara', 'Pull&Bear']),
            "price" => $this->faker->numberBetween(100, 500),
            "image" => $this->faker->randomElement(['1.jpg', '2.jpg', '3.jpg','4.jpg', '5.jpg', '6.jpg']),
            "quantity" => $this->faker->numberBetween(10, 100),
            "user_id" => $this->faker->numberBetween(1, 3),
            "category_id" => $this->faker->numberBetween(1,2)
        ];
    }
}
