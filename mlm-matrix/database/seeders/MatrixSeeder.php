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
     * Tạo cấu trúc Single-Level Matrix với 1 admin và nhiều users trực tiếp dưới admin.
     */
    public function run(): void
    {
        $this->command->info('🌳 Seeding Single-Level Matrix Structure...');

        // Tạo admin user nếu chưa tồn tại
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::create([
                'email' => 'admin@mlm.com',
                'password' => Hash::make('password'),
                'fullname' => 'Administrator',
                'role' => 'admin',
                'provider' => 'local',
                'active' => true,
            ]);
            $this->command->info('✅ Admin user created: admin@mlm.com');
        }

        // Đảm bảo admin có node (root node)
        if (!$admin->node) {
            Node::create([
                'user_id' => $admin->id,
                'position' => 0,
                'depth' => 0,
                'parent_id' => null,
                '_lft' => 1,
                '_rgt' => 2,
            ]);
            $this->command->info('✅ Root node created for admin');
        }

        // Refresh admin để load relationship node
        $admin->refresh();

        $placementService = app(PlacementService::class);

        // Tạo danh sách users mẫu với nhiều người hơn để test khả năng xử lý 1000 users
        $sampleUsers = [];

        // Tạo 1000 users mẫu để test khả năng xử lý large dataset
        for ($i = 1; $i <= 1000; $i++) {
            $firstNames = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Đỗ', 'Vũ', 'Bùi', 'Đinh', 'Ngô', 'Đặng', 'Đoàn', 'Trịnh', 'Lý', 'Tạ', 'Tô'];
            $lastNames = ['An', 'Bình', 'Cường', 'Dung', 'Em', 'Phương', 'Giang', 'Hoa', 'Minh', 'Lan', 'Nam', 'Oanh', 'Phong', 'Quỳnh', 'Rừng', 'Sen', 'Tùng', 'Uyên', 'Vũ', 'Xuân'];

            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $fullname = $firstName . ' ' . $lastName;

            $sampleUsers[] = [
                'fullname' => $fullname,
                'email' => strtolower(str_replace(' ', '.', $fullname)) . $i . '@example.com',
                'sponsor' => $admin
            ];
        }

        $createdCount = 0;
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

                // Đặt user vào matrix trực tiếp dưới admin (single level)
                $placementService->place($user, $userData['sponsor']);
                $createdCount++;
            }
        }

        // Hiển thị thống kê
        $totalUsers = User::count();
        $totalNodes = Node::count();
        $adminDownlines = $admin->node ? $admin->node->children()->count() : 0;

        $this->command->info('✅ Matrix seeding completed successfully!');
        $this->command->info("📊 Statistics:");
        $this->command->info("   • Total Users: {$totalUsers}");
        $this->command->info("   • Total Nodes: {$totalNodes}");
        $this->command->info("   • Admin Direct Downlines: {$adminDownlines}");
        $this->command->info("   • New Users Created: {$createdCount}");
        $this->command->info('');
        $this->command->info('🎯 Single-Level Matrix Structure:');
        $this->command->info('   • 1 Admin (Root)');
        $this->command->info('   • ' . $adminDownlines . ' Direct Members under Admin');
        $this->command->info('   • No sub-levels (single level only)');
        $this->command->info('');
        $this->command->info('💡 Login credentials:');
        $this->command->info('   • Admin: admin@mlm.com / password');
        $this->command->info('   • Members: [name]@example.com / password');
        $this->command->info('');
        $this->command->info('🔍 Sample of created users (showing first 5 of ' . $adminDownlines . '):');
        $sampleUsers = User::where('email', 'like', '%@example.com')->limit(5)->get();
        foreach ($sampleUsers as $user) {
            $this->command->info('   • ' . $user->fullname . ' (' . $user->email . ')');
        }
    }
}
