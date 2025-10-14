<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PolicyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of policies.
     */
    public function index(Request $request)
    {
        try {
            $policies = Setting::where('key', 'like', 'policy_%')
                ->orderBy('updated_at', 'desc')
                ->paginate(15);

            if ($request->expectsJson()) {
                return response()->json([
                    'policies' => $policies
                ]);
            }

            return view('admin.policies.index', compact('policies'));

        } catch (\Exception $e) {
            Log::error('Failed to load policies', [
                'error' => $e->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Không thể tải danh sách chính sách.',
                ], 500);
            }

            return redirect()->back()->with('error', 'Không thể tải danh sách chính sách.');
        }
    }

    /**
     * Show the form for creating a new policy.
     */
    public function create()
    {
        return view('admin.policies.create');
    }

    /**
     * Store a newly created policy.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'value' => ['required', 'string'],
            'type' => ['required', 'in:string,integer,boolean,json'],
        ]);

        try {
            $key = 'policy_' . \Illuminate\Support\Str::slug($validated['name']);

            // Check if key already exists
            if (Setting::where('key', $key)->exists()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Chính sách với tên này đã tồn tại.');
            }

            Setting::create([
                'key' => $key,
                'value' => $validated['value'],
                'type' => $validated['type'],
            ]);

            Log::info('Policy created', [
                'created_by' => auth()->id(),
                'key' => $key,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Chính sách đã được tạo thành công.',
                ], 201);
            }

            return redirect()->route('admin.policies.index')
                ->with('success', 'Chính sách đã được tạo thành công.');

        } catch (\Exception $e) {
            Log::error('Failed to create policy', [
                'error' => $e->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Không thể tạo chính sách.',
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Không thể tạo chính sách.');
        }
    }

    /**
     * Show the form for editing the specified policy.
     */
    public function edit($id)
    {
        $policy = Setting::findOrFail($id);

        return view('admin.policies.edit', compact('policy'));
    }

    /**
     * Update the specified policy.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'value' => ['required', 'string'],
            'type' => ['required', 'in:string,integer,boolean,json'],
        ]);

        try {
            $policy = Setting::findOrFail($id);

            $policy->update([
                'value' => $validated['value'],
                'type' => $validated['type'],
            ]);

            Log::info('Policy updated', [
                'updated_by' => auth()->id(),
                'policy_id' => $id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Chính sách đã được cập nhật thành công.',
                ]);
            }

            return redirect()->route('admin.policies.index')
                ->with('success', 'Chính sách đã được cập nhật thành công.');

        } catch (\Exception $e) {
            Log::error('Failed to update policy', [
                'error' => $e->getMessage(),
                'policy_id' => $id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Không thể cập nhật chính sách.',
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Không thể cập nhật chính sách.');
        }
    }

    /**
     * Remove the specified policy.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $policy = Setting::findOrFail($id);

            // Prevent deletion of system policies
            if (str_starts_with($policy->key, 'mlm_')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Không thể xóa chính sách hệ thống.',
                    ], 403);
                }

                return redirect()->back()
                    ->with('error', 'Không thể xóa chính sách hệ thống.');
            }

            $policy->delete();

            Log::info('Policy deleted', [
                'deleted_by' => auth()->id(),
                'policy_id' => $id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Chính sách đã được xóa thành công.',
                ]);
            }

            return redirect()->route('admin.policies.index')
                ->with('success', 'Chính sách đã được xóa thành công.');

        } catch (\Exception $e) {
            Log::error('Failed to delete policy', [
                'error' => $e->getMessage(),
                'policy_id' => $id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Không thể xóa chính sách.',
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Không thể xóa chính sách.');
        }
    }
}

