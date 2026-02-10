<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $counties = [
            'Nairobi',
            'Kiambu',
            'Mombasa',
            'Nakuru',
            'Kisumu',
            'Uasin Gishu',
        ];

        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(3),
            'condition' => fake()->randomElement(Item::CONDITIONS),
            'price' => fake()->numberBetween(0, 150000),
            'negotiable' => fake()->boolean(),
            'county' => fake()->randomElement($counties),
            'town' => fake()->city(),
            'status' => 'draft',
            'views_count' => 0,
        ];
    }
}
