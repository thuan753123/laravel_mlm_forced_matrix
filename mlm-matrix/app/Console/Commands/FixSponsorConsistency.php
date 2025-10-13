<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\PlacementService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FixSponsorConsistency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-sponsor-consistency {--dry-run : Show what would be fixed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix sponsor consistency issues where users are not placed under their correct sponsors in the matrix';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $placementService = app(PlacementService::class);

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE: Checking for sponsor consistency issues...');
        } else {
            $this->info('🔧 FIXING SPONSOR CONSISTENCY ISSUES...');
        }

        $users = User::with(['referredBy.node', 'node'])->get();
        $totalUsers = $users->count();
        $inconsistentUsers = 0;
        $fixedUsers = 0;

        $this->output->progressStart($totalUsers);

        foreach ($users as $user) {
            $this->output->progressAdvance();

            if ($placementService->validateSponsorConsistency($user)) {
                continue; // User is already consistent
            }

            $inconsistentUsers++;

            if ($dryRun) {
                $sponsor = $user->referredBy;
                $this->warn("❌ User {$user->id} ({$user->email}) should be under sponsor {$sponsor?->id} ({$sponsor?->email}) but is not");
                continue;
            }

            // Try to fix the inconsistency
            if ($placementService->fixSponsorConsistency($user)) {
                $fixedUsers++;
                $this->info("✅ Fixed user {$user->id} ({$user->email})");
            } else {
                $this->error("❌ Failed to fix user {$user->id} ({$user->email})");
            }
        }

        $this->output->progressFinish();

        $this->newLine();

        if ($dryRun) {
            $this->info("📊 DRY RUN RESULTS:");
            $this->line("Total users checked: {$totalUsers}");
            $this->line("Inconsistent users found: {$inconsistentUsers}");
            $this->line("Users that would be fixed: {$inconsistentUsers}");
        } else {
            $this->info("📊 FIX RESULTS:");
            $this->line("Total users checked: {$totalUsers}");
            $this->line("Inconsistent users found: {$inconsistentUsers}");
            $this->line("Users successfully fixed: {$fixedUsers}");
            $this->line("Users failed to fix: " . ($inconsistentUsers - $fixedUsers));

            Log::info('Sponsor consistency fix completed', [
                'total_users' => $totalUsers,
                'inconsistent_users' => $inconsistentUsers,
                'fixed_users' => $fixedUsers,
            ]);
        }

        if ($inconsistentUsers === 0) {
            $this->info('🎉 All users have consistent sponsor relationships!');
        } elseif ($dryRun) {
            $this->warn('⚠️  Run without --dry-run to fix these issues.');
        }
    }
}
