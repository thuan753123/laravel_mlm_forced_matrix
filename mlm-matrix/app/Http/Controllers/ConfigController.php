<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateConfigRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('admin');
    }

    /**
     * Get MLM configuration.
     */
    public function index(): JsonResponse
    {
        try {
            $config = config('mlm');
            $settings = Setting::getMlmSettings();
            
            // Merge config with database settings
            $mergedConfig = array_merge($config, $settings);
            
            return response()->json([
                'config' => $mergedConfig,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get MLM config', [
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy cấu hình MLM.',
            ], 500);
        }
    }
    
    /**
     * Update MLM configuration.
     */
    public function update(UpdateConfigRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            
            // Convert array keys to database format
            $settings = [];
            foreach ($data as $key => $value) {
                $settings["mlm_{$key}"] = $value;
            }
            
            // Update settings in database
            Setting::updateMlmSettings($settings);
            
            Log::info('MLM config updated', [
                'updated_by' => auth()->id(),
                'settings' => $settings,
            ]);
            
            return response()->json([
                'message' => 'Cấu hình MLM đã được cập nhật thành công.',
                'config' => $data,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to update MLM config', [
                'error' => $e->getMessage(),
                'data' => $request->validated(),
            ]);
            
            return response()->json([
                'message' => 'Cập nhật cấu hình MLM thất bại.',
            ], 500);
        }
    }
    
    /**
     * Reset MLM configuration to default.
     */
    public function reset(): JsonResponse
    {
        try {
            $defaultConfig = config('mlm');
            
            // Convert to database format
            $settings = [];
            foreach ($defaultConfig as $key => $value) {
                $settings["mlm_{$key}"] = $value;
            }
            
            // Update settings
            Setting::updateMlmSettings($settings);
            
            Log::info('MLM config reset to default', [
                'reset_by' => auth()->id(),
            ]);
            
            return response()->json([
                'message' => 'Cấu hình MLM đã được đặt lại về mặc định.',
                'config' => $defaultConfig,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to reset MLM config', [
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'message' => 'Đặt lại cấu hình MLM thất bại.',
            ], 500);
        }
    }
    
    /**
     * Get configuration history.
     */
    public function history(): JsonResponse
    {
        try {
            $history = Setting::where('key', 'like', 'mlm_%')
                ->orderBy('updated_at', 'desc')
                ->limit(50)
                ->get()
                ->map(function ($setting) {
                    return [
                        'key' => $setting->key,
                        'value' => $setting->value,
                        'type' => $setting->type,
                        'updated_at' => $setting->updated_at,
                    ];
                });
            
            return response()->json([
                'history' => $history,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get config history', [
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'message' => 'Không thể lấy lịch sử cấu hình.',
            ], 500);
        }
    }
    
    /**
     * Validate configuration.
     */
    public function validateConfig(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            
            // Basic validation
            $errors = [];
            
            if (isset($data['width']) && ($data['width'] < 1 || $data['width'] > 10)) {
                $errors['width'] = ['Bề ngang phải từ 1 đến 10.'];
            }
            
            if (isset($data['max_depth']) && ($data['max_depth'] < 1 || $data['max_depth'] > 20)) {
                $errors['max_depth'] = ['Số tầng phải từ 1 đến 20.'];
            }
            
            if (isset($data['spillover']) && !in_array($data['spillover'], ['bfs', 'balanced', 'leftmost'])) {
                $errors['spillover'] = ['Chế độ spillover không hợp lệ.'];
            }
            
            if (isset($data['period']) && !in_array($data['period'], ['daily', 'weekly', 'monthly'])) {
                $errors['period'] = ['Chu kỳ không hợp lệ.'];
            }
            
            if (isset($data['commissions']) && is_array($data['commissions'])) {
                foreach ($data['commissions'] as $level => $rate) {
                    if ($rate < 0 || $rate > 1) {
                        $errors["commissions.{$level}"] = ["Tỷ lệ hoa hồng tầng {$level} phải từ 0 đến 1."];
                    }
                }
            }
            
            if (empty($errors)) {
                return response()->json([
                    'valid' => true,
                    'message' => 'Cấu hình hợp lệ.',
                ]);
            }
            
            return response()->json([
                'valid' => false,
                'errors' => $errors,
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Config validation failed', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            
            return response()->json([
                'message' => 'Xác thực cấu hình thất bại.',
            ], 500);
        }
    }
}