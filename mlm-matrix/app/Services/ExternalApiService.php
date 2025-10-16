<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalApiService
{
    protected $baseUrl;
    protected $timeout;

    public function __construct()
    {
        $this->baseUrl = config('mlm.external_api.url', 'http://localhost:3000/api/v1');
        $this->timeout = config('mlm.external_api.timeout', 30);
    }

    /**
     * Fetch users from external API
     */
    public function fetchUsers(int $page = 0, int $size = 10, ?string $referralCode = null): array
    {
        try {
            $query = [
                'page' => $page,
                'size' => $size,
            ];
            if (!empty($referralCode)) {
                $query['referral_code'] = $referralCode;
            }

            $response = Http::timeout($this->timeout)
                ->get($this->baseUrl . '/users', $query);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Successfully fetched users from external API', [
                    'page' => $page,
                    'size' => $size,
                    'referral_code' => $referralCode,
                    'total_elements' => $data['paging']['totalElements'] ?? 0,
                    'total_pages' => $data['paging']['totalPages'] ?? 0,
                ]);

                return $data;
            } else {
                Log::error('Failed to fetch users from external API', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                
                throw new \Exception("API request failed with status: {$response->status()}");
            }
        } catch (\Exception $e) {
            Log::error('Exception while fetching users from external API', [
                'error' => $e->getMessage(),
                'page' => $page,
                'size' => $size,
            ]);
            
            throw $e;
        }
    }

    /**
     * Fetch all users from external API (paginated)
     */
    public function fetchAllUsers(): array
    {
        $allUsers = [];
        $page = 0;
        $size = 100; // Fetch in batches of 100
        $totalPages = 1;

        do {
            $data = $this->fetchUsers($page, $size);
            $allUsers = array_merge($allUsers, $data['data'] ?? []);
            
            $totalPages = $data['paging']['totalPages'] ?? 1;
            $page++;
            
            // Add small delay to avoid overwhelming the API
            if ($page < $totalPages) {
                sleep(1);
            }
            
        } while ($page < $totalPages);

        Log::info('Completed fetching all users from external API', [
            'total_users' => count($allUsers),
            'total_pages' => $totalPages,
        ]);

        return $allUsers;
    }


    /**
     * Fetch users that match any of the provided referral codes
     */
    public function fetchUsersByReferralCodes(array $referralCodes): array
    {
        $allMatchingUsers = [];
        
        foreach ($referralCodes as $referralCode) {
            try {
                $users = $this->fetchUsersByReferralCode($referralCode);
                $allMatchingUsers = array_merge($allMatchingUsers, $users);
            } catch (\Exception $e) {
                Log::warning('Failed to fetch users for referral code', [
                    'referral_code' => $referralCode,
                    'error' => $e->getMessage(),
                ]);
                // Continue with other referral codes
            }
        }
        
        return $allMatchingUsers;
    }

    /**
     * Fetch users by referral code (optimized - only fetch first few pages)
     */
    public function fetchUsersByReferralCode(string $referralCode): array
    {
        try {
            // Prefer server-side filtering via referral_code param
            $page = 0;
            $size = 100;
            $allUsers = [];
            do {
                $response = $this->fetchUsers($page, $size, $referralCode);
                $allUsers = array_merge($allUsers, $response['data'] ?? []);
                $totalPages = $response['paging']['totalPages'] ?? ($page + 1);
                $page++;
            } while ($page < min($totalPages, 3)); // cap to 3 pages for safety

            Log::info('Fetched users by referral code (server-filtered)', [
                'referral_code' => $referralCode,
                'returned' => count($allUsers),
            ]);
            
            return array_values($allUsers);
            
        } catch (\Exception $e) {
            Log::error('Failed to fetch users by referral code', [
                'referral_code' => $referralCode,
                'error' => $e->getMessage(),
            ]);
            
            // Return empty array instead of throwing exception
            return [];
        }
    }

    /**
     * Fetch users by referral code with pagination (optimized for downline API)
     */
    public function fetchUsersByReferralCodePaginated(string $referralCode, int $page, int $perPage, string $search = ''): array
    {
        try {
            // Use server-side filtering and pagination directly
            $apiPage = max(0, $page - 1); // API is 0-based
            $data = $this->fetchUsers($apiPage, $perPage, $referralCode);
            $users = $data['data'] ?? [];
            $total = $data['paging']['totalElements'] ?? count($users);
            
            // Format users for response
            $formattedUsers = [];
            foreach ($users as $index => $apiUser) {
                $fullname = $apiUser['fullname'] ?? '';
                $email = $apiUser['email'] ?? '';
                
                $formattedUsers[] = [
                    'id' => $apiUser['id'],
                    'user_id' => $apiUser['id'],
                    'fullname' => $fullname,
                    'email' => $email,
                    'referral_code' => $apiUser['referralCode'] ?? '',
                    'position' => ($apiPage * $perPage) + $index + 1,
                    'depth' => 1,
                    'created_at' => $apiUser['createdAt'] ?? null, // legacy snake_case
                    'createdAt' => $apiUser['createdAt'] ?? null,   // preferred camelCase for UI
                    'is_active' => $apiUser['active'] ?? true,
                    'avatar' => mb_substr($fullname ?: $email, 0, 1),
                    'avatar_url' => $apiUser['avatarUrl'] ?? null,
                    'phone_number' => $apiUser['phoneNumber'] ?? null,
                    'plan' => $apiUser['plan'] ?? 'FREE',
                ];
            }
            
            Log::info('Fetched paginated users by referral code', [
                'referral_code' => $referralCode,
                'page' => $page,
                'per_page' => $perPage,
                'total_matching' => $total,
                'returned' => count($formattedUsers),
            ]);
            
            return [
                'data' => $formattedUsers,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'last_page' => ceil($total / $perPage),
                    'from' => $total > 0 ? ($apiPage * $perPage) + 1 : 0,
                    'to' => min(($apiPage * $perPage) + $perPage, $total),
                    'has_more_pages' => $page < ceil($total / $perPage),
                ],
                'summary' => [
                    'total_downlines' => $total,
                    'active_downlines' => count(array_filter($users, function ($user) {
                        return $user['active'] ?? true;
                    })),
                ],
            ];
            
        } catch (\Exception $e) {
            Log::error('Failed to fetch paginated users by referral code', [
                'referral_code' => $referralCode,
                'page' => $page,
                'per_page' => $perPage,
                'error' => $e->getMessage(),
            ]);
            
            // Return empty result instead of throwing exception
            return [
                'data' => [],
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => 0,
                    'last_page' => 0,
                    'from' => 0,
                    'to' => 0,
                    'has_more_pages' => false,
                ],
                'summary' => [
                    'total_downlines' => 0,
                    'active_downlines' => 0,
                ],
            ];
        }
    }

    /**
     * Alternative method: Fetch all users and filter by referral codes locally
     * Use this if the external API doesn't support filtering by referralCode
     */
    public function fetchAllUsersAndFilterByReferralCodes(array $referralCodes): array
    {
        $allUsers = $this->fetchAllUsers();
        
        // Filter users whose referralCode matches any of the provided codes
        $matchingUsers = array_filter($allUsers, function ($user) use ($referralCodes) {
            return !empty($user['referralCode']) && in_array($user['referralCode'], $referralCodes);
        });
        
        Log::info('Filtered users by referral codes', [
            'referral_codes' => $referralCodes,
            'total_users' => count($allUsers),
            'matching_users' => count($matchingUsers),
        ]);
        
        return array_values($matchingUsers);
    }

    /**
     * Test API connection
     */
    public function testConnection(): bool
    {
        try {
            $response = Http::timeout(10)->get($this->baseUrl . '/users', [
                'page' => 0,
                'size' => 1,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('API connection test failed', [
                'error' => $e->getMessage(),
                'url' => $this->baseUrl,
            ]);
            
            return false;
        }
    }

    /**
     * Fetch payments from external API with optional referral_code filter
     */
    public function fetchPayments(int $page = 0, int $size = 20, ?string $referralCode = null, ?string $status = null, ?string $search = null): array
    {
        try {
            $query = [
                'page' => $page,
                'size' => $size,
            ];
            if (!empty($referralCode)) {
                $query['referral_code'] = $referralCode;
            }
            if (!empty($status)) {
                $query['status'] = $status; // SUCCESS | PENDING | FAILED
            }
            if (!empty($search)) {
                $query['search'] = $search; // only if API supports
            }

            $response = Http::timeout($this->timeout)
                ->get($this->baseUrl . '/payments', $query);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Fetched payments from external API', [
                    'page' => $page,
                    'size' => $size,
                    'referral_code' => $referralCode,
                    'total_elements' => $data['paging']['totalElements'] ?? 0,
                ]);
                return $data;
            }

            Log::error('Failed to fetch payments from external API', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception("Payments API failed with status: {$response->status()}");
        } catch (\Exception $e) {
            Log::error('Exception while fetching payments from external API', [
                'error' => $e->getMessage(),
                'page' => $page,
                'size' => $size,
                'referral_code' => $referralCode,
            ]);
            throw $e;
        }
    }

    /**
     * Fetch payments (paginated) and map to UI-friendly structure
     */
    public function fetchPaymentsPaginated(string $referralCode, int $page, int $perPage, ?string $status = null, ?string $search = null): array
    {
        try {
            $apiPage = max(0, $page - 1);
            $data = $this->fetchPayments($apiPage, $perPage, $referralCode, $status, $search);
            $payments = $data['data'] ?? [];
            $total = $data['paging']['totalElements'] ?? count($payments);

            $formatted = array_map(function ($p) use ($apiPage, $perPage) {
                return [
                    'id' => $p['id'] ?? null,
                    'provider_txn_ref' => $p['providerTxnRef'] ?? null,
                    'amount' => $p['amount'] ?? 0,
                    'status' => $p['status'] ?? 'PENDING',
                    'response_code' => $p['responseCode'] ?? null,
                    'message' => $p['message'] ?? null,
                    'bank_code' => $p['bankCode'] ?? null,
                    'pay_date' => $p['payDate'] ?? null,
                    'created_at' => $p['createdAt'] ?? null,
                    'createdAt' => $p['createdAt'] ?? null,
                    'updatedAt' => $p['updatedAt'] ?? null,
                    'user_id' => $p['userId'] ?? null,
                    'user_fullname' => $p['userFullname'] ?? null,
                    'user_email' => $p['userEmail'] ?? null,
                    'user_phone_number' => $p['userPhoneNumber'] ?? null,
                ];
            }, $payments);

            return [
                'data' => $formatted,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'last_page' => (int) ceil($total / $perPage),
                    'from' => $total > 0 ? ($apiPage * $perPage) + 1 : 0,
                    'to' => min(($apiPage * $perPage) + $perPage, $total),
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Failed to fetch payments paginated', [
                'error' => $e->getMessage(),
                'referral_code' => $referralCode,
                'page' => $page,
                'per_page' => $perPage,
            ]);
            return [
                'data' => [],
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => 0,
                    'last_page' => 0,
                    'from' => 0,
                    'to' => 0,
                ],
            ];
        }
    }
}
