<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Services\PlacementService;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@mlm.com'],
            [
                'email' => 'admin@mlm.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'fullname' => 'MLM Admin',
                'active' => true,
                'referral_code' => 'ADMIN0001',
            ]
        );

        // Create root node for admin if it doesn't exist
        if (!$admin->node) {
            $placementService = app(PlacementService::class);
            $placementService->place($admin);
        }
        
        for ($i = 1; $i <= 50; $i++) {
            User::updateOrCreate(
                ['email' => "4168{$i}@ai168.vn"],
                [
                    'password' => Hash::make('123123123'),
                    'role' => 'member',
                    'fullname' => "User {$i}",
                    'active' => true,
                    'referral_code' => "4168{$i}",
                ]
            );
        }

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@mlm.com');
        $this->command->info('Password: password');
        $this->command->info('Referral Code: ADMIN0001');
    }
}
