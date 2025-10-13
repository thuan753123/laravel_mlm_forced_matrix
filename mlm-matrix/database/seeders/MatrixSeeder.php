<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Node;
use App\Services\PlacementService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MatrixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy admin user hiện tại
        $admin = User::where('role', 'admin')->first();

        // Đảm bảo admin có node
        if ($admin && !$admin->node) {
            Node::create([
                'user_id' => $admin->id,
                'position' => 0,
                'depth' => 0,
                'parent_id' => null,
                '_lft' => 1,
                '_rgt' => 2,
            ]);
        }

        $placementService = app(PlacementService::class);

        // Tạo một số users mẫu để xây dựng cây matrix (chỉ tạo nếu chưa tồn tại)
        $sampleUsers = [
            ['fullname' => 'Nguyễn Văn A', 'email' => 'user1@example.com'],
            ['fullname' => 'Trần Thị B', 'email' => 'user2@example.com'],
            ['fullname' => 'Lê Văn C', 'email' => 'user3@example.com'],
            ['fullname' => 'Phạm Thị D', 'email' => 'user4@example.com'],
            ['fullname' => 'Hoàng Văn E', 'email' => 'user5@example.com'],
            ['fullname' => 'Đỗ Thị F', 'email' => 'user6@example.com'],
            ['fullname' => 'Vũ Văn G', 'email' => 'user7@example.com'],
            ['fullname' => 'Bùi Thị H', 'email' => 'user8@example.com'],
            ['fullname' => 'Đinh Văn I', 'email' => 'user9@example.com'],
            ['fullname' => 'Ngô Thị K', 'email' => 'user10@example.com'],
        ];

        foreach ($sampleUsers as $userData) {
            $existingUser = User::where('email', $userData['email'])->first();
            if (!$existingUser) {
                $user = User::create([
                    'email' => $userData['email'],
                    'password' => Hash::make('password'),
                    'fullname' => $userData['fullname'],
                    'role' => 'member',
                    'provider' => 'local',
                    'active' => true,
                ]);

                // Đặt user vào matrix
                $placementService->place($user);
            }
        }

        $this->command->info('Matrix sample data created successfully!');
        $this->command->info('Total users: ' . User::count());
        $this->command->info('Total nodes: ' . Node::count());
    }
}
