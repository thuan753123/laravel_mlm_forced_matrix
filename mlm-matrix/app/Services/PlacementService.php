<?php

namespace App\Services;

use App\Models\User;
use App\Models\Node;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlacementService
{
    /**
     * Place a user in the forced matrix structure.
     */
    public function place(User $user, ?User $sponsor = null): Node
    {
        return DB::transaction(function () use ($user, $sponsor) {
            $config = config('mlm');
            $width = $config['width'];
            $maxDepth = $config['max_depth'];

            // If no sponsor provided, find the root or create one
            if (!$sponsor) {
                $sponsor = $this->findRootSponsor();
            }

            // Ensure user is placed under their correct sponsor in the matrix
            $position = $this->findBestPositionUnderSponsor($sponsor, $width, $maxDepth);

            // Create the node
            $node = Node::create([
                'user_id' => $user->id,
                'position' => $position['position'],
                'depth' => $position['depth'],
                'parent_id' => $position['parent_id'],
            ]);

            // Update the nested set structure
            $this->updateNestedSetStructure($node);

            Log::info('User placed in matrix', [
                'user_id' => $user->id,
                'node_id' => $node->id,
                'parent_id' => $node->parent_id,
                'position' => $node->position,
                'depth' => $node->depth,
                'sponsor_id' => $sponsor->id,
            ]);

            return $node;
        });
    }
    
    /**
     * Find the root sponsor (admin user).
     */
    private function findRootSponsor(): User
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            // If no admin exists, find any user to be the root (first user registered)
            $admin = User::first();

            if (!$admin) {
                throw new \Exception('No users found in the system.');
            }

            // Promote this user to admin
            $admin->update(['role' => 'admin']);
        }

        // Ensure admin has a node
        if (!$admin->node) {
            $this->createRootNode($admin);
        }

        return $admin;
    }
    
    /**
     * Create root node for admin.
     */
    private function createRootNode(User $admin): Node
    {
        // Check if root node already exists
        $existingRoot = Node::where('parent_id', null)->first();
        if ($existingRoot) {
            return $existingRoot;
        }

        return Node::create([
            'user_id' => $admin->id,
            'position' => 0,
            'depth' => 0,
            'parent_id' => null,
            '_lft' => 1,
            '_rgt' => 2,
        ]);
    }
    
    /**
     * Find the best position for a new user under their sponsor using BFS.
     */
    private function findBestPositionUnderSponsor(User $sponsor, int $width, int $maxDepth): array
    {
        $sponsorNode = $sponsor->node;

        if (!$sponsorNode) {
            throw new \Exception('Sponsor does not have a node in the matrix.');
        }

        // First, try to place directly under the sponsor
        $childrenCount = $sponsorNode->children()->count();

        if ($childrenCount < $width && $sponsorNode->depth < $maxDepth) {
            return [
                'parent_id' => $sponsorNode->id,
                'position' => $childrenCount,
                'depth' => $sponsorNode->depth + 1,
            ];
        }

        // If sponsor is full, use BFS to find the first available position in sponsor's downline
        $queue = [$sponsorNode];
        $visited = [];

        while (!empty($queue)) {
            $currentNode = array_shift($queue);

            if (in_array($currentNode->id, $visited)) {
                continue;
            }

            $visited[] = $currentNode->id;

            // Check if current node has space for children and is within sponsor's downline
            $childrenCount = $currentNode->children()->count();

            if ($childrenCount < $width && $currentNode->depth < $maxDepth) {
                return [
                    'parent_id' => $currentNode->id,
                    'position' => $childrenCount,
                    'depth' => $currentNode->depth + 1,
                ];
            }

            // Add children to queue for BFS (only within sponsor's downline)
            $children = $currentNode->children()->orderBy('position')->get();
            foreach ($children as $child) {
                if (!in_array($child->id, $visited)) {
                    $queue[] = $child;
                }
            }
        }

        // If no position found in sponsor's downline, place under the sponsor (even if it exceeds width)
        // This handles edge cases where the matrix becomes unbalanced
        $childrenCount = $sponsorNode->children()->count();
        return [
            'parent_id' => $sponsorNode->id,
            'position' => $childrenCount,
            'depth' => $sponsorNode->depth + 1,
        ];
    }
    
    /**
     * Update the nested set structure after adding a new node.
     */
    private function updateNestedSetStructure(Node $node): void
    {
        if ($node->parent_id) {
            $parent = Node::find($node->parent_id);
            $rightSibling = $parent->children()
                ->where('position', '>', $node->position)
                ->orderBy('position')
                ->first();
            
            if ($rightSibling) {
                // Insert before right sibling
                $node->_lft = $rightSibling->_lft;
                $node->_rgt = $rightSibling->_lft + 1;
                
                // Update all nodes to the right
                Node::where('_lft', '>=', $rightSibling->_lft)
                    ->where('id', '!=', $node->id)
                    ->increment('_lft', 2);
                Node::where('_rgt', '>=', $rightSibling->_lft)
                    ->where('id', '!=', $node->id)
                    ->increment('_rgt', 2);
            } else {
                // Insert as last child
                $node->_lft = $parent->_rgt;
                $node->_rgt = $parent->_rgt + 1;
                
                // Update parent's right boundary
                $parent->increment('_rgt', 2);
            }
        } else {
            // Root node
            $node->_lft = 1;
            $node->_rgt = 2;
        }
        
        $node->save();
    }
    
    /**
     * Get the upline chain for a user.
     */
    public function getUplineChain(User $user): array
    {
        $node = $user->node;

        if (!$node) {
            return [];
        }

        $upline = [];
        $current = $node->parent;

        while ($current) {
            if ($current->user) {
                $upline[] = $current->user;
            }
            $current = $current->parent;
        }

        return $upline;
    }
    
    /**
     * Get the downline for a user.
     */
    public function getDownline(User $user, int $maxDepth = null): array
    {
        $node = $user->node;

        if (!$node) {
            return [];
        }

        $query = $node->descendants();

        if ($maxDepth !== null) {
            $query->where('depth', '<=', $node->depth + $maxDepth);
        }

        // Chỉ hiển thị 2 tầng
        $maxDepth = $node->depth + 2;
        $query->where('depth', '<=', $maxDepth);

        return $query->with('user')->get()->map(function ($node) {
            return $node->user;
        })->filter()->toArray();
    }
    
    /**
     * Validate that a user's matrix placement is consistent with their sponsor.
     */
    public function validateSponsorConsistency(User $user): bool
    {
        $node = $user->node;

        if (!$node) {
            return true; // No node means no inconsistency to check
        }

        $sponsor = $user->referredBy;

        if (!$sponsor || !$sponsor->node) {
            return true; // No sponsor or sponsor has no node means no inconsistency
        }

        // Check if the user's node is in the sponsor's downline
        $sponsorDescendants = $sponsor->node->descendants()->pluck('user_id')->toArray();
        $sponsorDescendants[] = $sponsor->id; // Include sponsor themselves

        return in_array($user->id, $sponsorDescendants);
    }

    /**
     * Fix sponsor consistency for a user by moving them under their correct sponsor if needed.
     */
    public function fixSponsorConsistency(User $user): bool
    {
        if ($this->validateSponsorConsistency($user)) {
            return true; // Already consistent
        }

        $sponsor = $user->referredBy;

        if (!$sponsor || !$sponsor->node) {
            Log::warning('Cannot fix sponsor consistency: user has no valid sponsor', [
                'user_id' => $user->id,
                'sponsor_id' => $sponsor?->id,
            ]);
            return false;
        }

        try {
            // Remove user from current position
            $currentNode = $user->node;
            if ($currentNode) {
                $currentNode->delete();
            }

            // Place user under correct sponsor
            $this->place($user, $sponsor);

            Log::info('Fixed sponsor consistency for user', [
                'user_id' => $user->id,
                'sponsor_id' => $sponsor->id,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to fix sponsor consistency', [
                'user_id' => $user->id,
                'sponsor_id' => $sponsor?->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get matrix statistics for a user.
     */
    public function getMatrixStats(User $user): array
    {
        $node = $user->node;

        if (!$node) {
            return [
                'total_downline' => 0,
                'direct_downline' => 0,
                'depth' => 0,
                'position' => 0,
            ];
        }

        return [
            'total_downline' => $node->descendants()->count(),
            'direct_downline' => $node->children()->count(),
            'depth' => $node->depth,
            'position' => $node->position,
        ];
    }
}