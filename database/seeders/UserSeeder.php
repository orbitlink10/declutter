<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Declutter Admin',
                'password' => Hash::make('admin123'),
                'phone' => '+254700000001',
                'county' => 'Nairobi',
                'town' => 'Westlands',
                'bio' => 'Platform administrator.',
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'seller@declutterkenya.test'],
            [
                'name' => 'Sample Seller',
                'password' => Hash::make('password'),
                'phone' => '+254700000002',
                'county' => 'Nakuru',
                'town' => 'Nakuru Town',
                'bio' => 'Decluttering quality household items.',
                'email_verified_at' => now(),
                'is_admin' => false,
            ]
        );

        User::factory(6)->create();
    }
}
