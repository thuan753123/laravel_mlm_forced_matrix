<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user if not exists
        if (User::count() === 0) {
            User::create([
                'email' => 'admin@mlm.com',
                'password' => Hash::make('password'),
                'fullname' => 'Administrator',
                'role' => 'admin',
                'provider' => 'local',
                'active' => true,
            ]);

            $this->command->info('Admin user created: admin@mlm.com / password');
        }

        // Seed matrix sample data
        $this->call(MatrixSeeder::class);
        
        // Seed discount tiers
        $this->call(DiscountTierSeeder::class);
    }
}
