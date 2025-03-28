<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2,10,1000),
            'stock' => $this->faker->numberBetween(1, 100),
            'category' => $this->faker->randomElement(['Eletrônicos', 'Roupas', 'Alimentos'])
        ];
    }
}
