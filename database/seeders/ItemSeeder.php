<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $users = User::query()->where('is_admin', false)->get();

        if ($categories->isEmpty() || $users->isEmpty()) {
            return;
        }

        $seedItems = [
            [
                'title' => 'Modern Grey Sofa Set',
                'description' => 'Three-seater sofa in excellent condition. Perfect for a small living room and recently steam cleaned.',
                'condition' => 'like_new',
                'price' => 35000,
                'negotiable' => true,
                'county' => 'Nairobi',
                'town' => 'Kilimani',
                'status' => 'active',
            ],
            [
                'title' => 'Samsung 43 inch Smart TV',
                'description' => 'Working perfectly with remote and wall mount included. Selling because we upgraded to a bigger set.',
                'condition' => 'good',
                'price' => 28000,
                'negotiable' => true,
                'county' => 'Kiambu',
                'town' => 'Ruiru',
                'status' => 'active',
            ],
            [
                'title' => 'Wooden Office Desk',
                'description' => 'Solid wood desk with drawers. A few scratches but still sturdy and ideal for home office setup.',
                'condition' => 'good',
                'price' => 7500,
                'negotiable' => false,
                'county' => 'Nakuru',
                'town' => 'Nakuru Town',
                'status' => 'sold',
            ],
            [
                'title' => 'Free Baby Cot',
                'description' => 'Baby cot available for pickup. Structurally good and includes mattress protector. Giving it away for free.',
                'condition' => 'fair',
                'price' => 0,
                'negotiable' => false,
                'county' => 'Mombasa',
                'town' => 'Nyali',
                'status' => 'active',
            ],
            [
                'title' => 'Old Microwave for Spares',
                'description' => 'Microwave no longer heats but lights and buttons work. Best for parts or someone who can repair.',
                'condition' => 'for_parts',
                'price' => 1500,
                'negotiable' => true,
                'county' => 'Kisumu',
                'town' => 'Milimani',
                'status' => 'removed',
            ],
            [
                'title' => 'Mountain Bike Size M',
                'description' => 'Well maintained mountain bike with new tires and serviced gears. Suitable for commuting or trails.',
                'condition' => 'good',
                'price' => 18000,
                'negotiable' => true,
                'county' => 'Nairobi',
                'town' => 'Kasarani',
                'status' => 'draft',
            ],
        ];

        foreach ($seedItems as $seedItem) {
            $user = $users->random();

            Item::query()->create([
                ...$seedItem,
                'user_id' => $user->id,
                'category_id' => $categories->random()->id,
                'contact_phone' => $user->phone,
                'views_count' => random_int(0, 220),
            ]);
        }

        Item::factory()->count(18)->make()->each(function (Item $item) use ($users, $categories): void {
            $user = $users->random();

            Item::query()->create([
                'user_id' => $user->id,
                'category_id' => $categories->random()->id,
                'title' => $item->title,
                'description' => $item->description,
                'condition' => $item->condition,
                'price' => $item->price,
                'negotiable' => $item->negotiable,
                'county' => $item->county,
                'town' => $item->town,
                'contact_phone' => $user->phone,
                'status' => fake()->randomElement(['active', 'active', 'sold', 'draft']),
                'views_count' => random_int(0, 320),
            ]);
        });
    }
}
