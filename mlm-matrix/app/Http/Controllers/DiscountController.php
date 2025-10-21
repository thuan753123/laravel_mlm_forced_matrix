<?php

namespace App\Http\Controllers;

use App\Models\DiscountTier;
use App\Services\DiscountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    protected $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    /**
     * Hiển thị danh sách các bậc chiết khấu
     */
    public function index()
    {
        $tiers = $this->discountService->getAllTiers();
        
        return view('admin.discounts.index', compact('tiers'));
    }

    /**
     * Tính chiết khấu dựa trên doanh thu
     */
    public function calculate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'revenue' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $revenue = (float) $request->input('revenue');
        $result = $this->discountService->calculateDiscount($revenue);
        $tierInfo = $this->discountService->getTierInfo($revenue);
        $nextTarget = $this->discountService->getNextTierTarget($revenue);

        return response()->json([
            'success' => true,
            'data' => [
                'calculation' => $result,
                'current_tier' => $tierInfo,
                'next_tier_target' => $nextTarget,
            ],
        ]);
    }

    /**
     * Hiển thị calculator UI
     */
    public function calculator()
    {
        $tiers = $this->discountService->getAllTiers();
        $examples = $this->discountService->getExamples();
        
        return view('admin.discounts.calculator', compact('tiers', 'examples'));
    }

    /**
     * API để lấy thông tin bậc chiết khấu
     */
    public function getTiers()
    {
        $tiers = $this->discountService->getAllTiers();
        
        return response()->json([
            'success' => true,
            'data' => $tiers,
        ]);
    }

    /**
     * Hiển thị ví dụ tính chiết khấu
     */
    public function examples()
    {
        $examples = $this->discountService->getExamples();
        
        return response()->json([
            'success' => true,
            'data' => $examples,
        ]);
    }

    /**
     * Hiển thị form tạo bậc chiết khấu mới (Admin only)
     */
    public function create()
    {
        return view('admin.discounts.create');
    }

    /**
     * Lưu bậc chiết khấu mới (Admin only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tier' => 'required|integer|min:1',
            'tier_name' => 'required|string|max:255',
            'revenue_from' => 'required|numeric|min:0',
            'revenue_to' => 'nullable|numeric|min:0|gt:revenue_from',
            'applicable_revenue_from' => 'required|numeric|min:0',
            'applicable_revenue_to' => 'nullable|numeric|min:0|gt:applicable_revenue_from',
            'discount_rate' => 'required|numeric|min:0|max:100',
            'note' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        DiscountTier::create($validated);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Đã thêm bậc chiết khấu mới thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa bậc chiết khấu (Admin only)
     */
    public function edit($id)
    {
        $tier = DiscountTier::findOrFail($id);
        
        return view('admin.discounts.edit', compact('tier'));
    }

    /**
     * Cập nhật bậc chiết khấu (Admin only)
     */
    public function update(Request $request, $id)
    {
        $tier = DiscountTier::findOrFail($id);

        $validated = $request->validate([
            'tier' => 'required|integer|min:1',
            'tier_name' => 'required|string|max:255',
            'revenue_from' => 'required|numeric|min:0',
            'revenue_to' => 'nullable|numeric|min:0|gt:revenue_from',
            'applicable_revenue_from' => 'required|numeric|min:0',
            'applicable_revenue_to' => 'nullable|numeric|min:0|gt:applicable_revenue_from',
            'discount_rate' => 'required|numeric|min:0|max:100',
            'note' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $tier->update($validated);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Đã cập nhật bậc chiết khấu thành công!');
    }

    /**
     * Xóa bậc chiết khấu (Admin only)
     */
    public function destroy($id)
    {
        $tier = DiscountTier::findOrFail($id);
        $tier->delete();

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Đã xóa bậc chiết khấu thành công!');
    }

    // ========== Member Routes ==========

    /**
     * Hiển thị danh sách bậc chiết khấu cho member
     */
    public function memberIndex()
    {
        $tiers = $this->discountService->getAllTiers();
        
        return view('discounts.index', compact('tiers'));
    }

    /**
     * Hiển thị calculator cho member
     */
    public function memberCalculator()
    {
        $tiers = $this->discountService->getAllTiers();
        $examples = $this->discountService->getExamples();
        
        return view('discounts.calculator', compact('tiers', 'examples'));
    }

    /**
     * Hiển thị chiết khấu hiện tại của user (mock data)
     */
    public function myDiscount(Request $request)
    {
        // Mock: Giả lập doanh thu của user (trong thực tế sẽ lấy từ orders)
        // Doanh thu = tổng orders thành công của user
        $mockRevenue = 850; // Mock: 850 triệu đồng
        
        // Tính chiết khấu dựa trên doanh thu
        $calculation = $this->discountService->calculateDiscount($mockRevenue);
        $currentTier = $this->discountService->getTierInfo($mockRevenue);
        $nextTarget = $this->discountService->getNextTierTarget($mockRevenue);
        $allTiers = $this->discountService->getAllTiers();
        
        return view('discounts.my-discount', compact(
            'mockRevenue',
            'calculation',
            'currentTier',
            'nextTarget',
            'allTiers'
        ));
    }
}

