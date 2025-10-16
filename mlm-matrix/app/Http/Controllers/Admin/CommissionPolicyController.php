<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommissionPolicyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of commission policies.
     */
    public function index(Request $request)
    {
        try {
            $commissions = Setting::where('key', 'like', 'commission_%')
                ->orderBy('updated_at', 'desc')
                ->paginate(15);

            if ($request->expectsJson()) {
                return response()->json([
                    'commissions' => $commissions
                ]);
            }

            return view('admin.commissions.index', compact('commissions'));

        } catch (\Exception $e) {
            Log::error('Failed to load commission policies', [
                'error' => $e->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Không thể tải danh sách chính sách hoa hồng.',
                ], 500);
            }

            return redirect()->back()->with('error', 'Không thể tải danh sách chính sách hoa hồng.');
        }
    }

    /**
     * Show the form for creating a new commission policy.
     */
    public function create()
    {
        return view('admin.commissions.create');
    }

    /**
     * Store a newly created commission policy.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'levels' => ['required', 'array'],
            'levels.*.rate' => ['required', 'numeric', 'min:0', 'max:1'],
            'levels.*.description' => ['nullable', 'string'],
        ]);

        try {
            $key = 'commission_' . \Illuminate\Support\Str::slug($validated['name']);

            // Check if key already exists
            if (Setting::where('key', $key)->exists()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Chính sách hoa hồng với tên này đã tồn tại.');
            }

            Setting::create([
                'key' => $key,
                'value' => json_encode([
                    'name' => $validated['name'],
                    'description' => $validated['description'] ?? '',
                    'levels' => $validated['levels'],
                ]),
                'type' => 'json',
            ]);

            Log::info('Commission policy created', [
                'created_by' => auth()->id(),
                'key' => $key,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Chính sách hoa hồng đã được tạo thành công.',
                ], 201);
            }

            return redirect()->route('admin.commissions.index')
                ->with('success', 'Chính sách hoa hồng đã được tạo thành công.');

        } catch (\Exception $e) {
            Log::error('Failed to create commission policy', [
                'error' => $e->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Không thể tạo chính sách hoa hồng.',
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Không thể tạo chính sách hoa hồng.');
        }
    }

    /**
     * Show the form for editing the specified commission policy.
     */
    public function edit($id)
    {
        $commission = Setting::findOrFail($id);

        $data = json_decode($commission->value, true);

        return view('admin.commissions.edit', compact('commission', 'data'));
    }

    /**
     * Update the specified commission policy.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'levels' => ['required', 'array'],
            'levels.*.rate' => ['required', 'numeric', 'min:0', 'max:1'],
            'levels.*.description' => ['nullable', 'string'],
        ]);

        try {
            $commission = Setting::findOrFail($id);

            $commission->update([
                'value' => json_encode([
                    'name' => $validated['name'],
                    'description' => $validated['description'] ?? '',
                    'levels' => $validated['levels'],
                ]),
                'type' => 'json',
            ]);

            Log::info('Commission policy updated', [
                'updated_by' => auth()->id(),
                'commission_id' => $id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Chính sách hoa hồng đã được cập nhật thành công.',
                ]);
            }

            return redirect()->route('admin.commissions.index')
                ->with('success', 'Chính sách hoa hồng đã được cập nhật thành công.');

        } catch (\Exception $e) {
            Log::error('Failed to update commission policy', [
                'error' => $e->getMessage(),
                'commission_id' => $id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Không thể cập nhật chính sách hoa hồng.',
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Không thể cập nhật chính sách hoa hồng.');
        }
    }

    /**
     * Remove the specified commission policy.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $commission = Setting::findOrFail($id);

            // Prevent deletion of active MLM commission
            if ($commission->key === 'mlm_commissions') {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Không thể xóa chính sách hoa hồng đang hoạt động.',
                    ], 403);
                }

                return redirect()->back()
                    ->with('error', 'Không thể xóa chính sách hoa hồng đang hoạt động.');
            }

            $commission->delete();

            Log::info('Commission policy deleted', [
                'deleted_by' => auth()->id(),
                'commission_id' => $id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Chính sách hoa hồng đã được xóa thành công.',
                ]);
            }

            return redirect()->route('admin.commissions.index')
                ->with('success', 'Chính sách hoa hồng đã được xóa thành công.');

        } catch (\Exception $e) {
            Log::error('Failed to delete commission policy', [
                'error' => $e->getMessage(),
                'commission_id' => $id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Không thể xóa chính sách hoa hồng.',
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Không thể xóa chính sách hoa hồng.');
        }
    }
}

