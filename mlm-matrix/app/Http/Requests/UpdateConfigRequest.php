<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConfigRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->canAccessAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'width' => ['nullable', 'integer', 'min:1', 'max:10'],
            'max_depth' => ['nullable', 'integer', 'min:1', 'max:20'],
            'spillover_mode' => ['nullable', 'string', 'in:bfs,balanced,leftmost'],
            'placement_mode' => ['nullable', 'string', 'in:forced,auto'],
            'capping_per_cycle' => ['nullable', 'numeric', 'min:0'],
            'cycle_period' => ['nullable', 'string', 'in:daily,weekly,monthly'],
            'commissions' => ['nullable', 'array'],
            'commissions.*' => ['numeric', 'min:0', 'max:1'],
            'min_personal_volume' => ['nullable', 'numeric', 'min:0'],
            'active_order_days' => ['nullable', 'integer', 'min:0'],
            'kyc_required' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'width.integer' => 'Bề ngang phải là số nguyên.',
            'width.min' => 'Bề ngang phải từ 1 trở lên.',
            'width.max' => 'Bề ngang không được vượt quá 10.',
            'max_depth.integer' => 'Số tầng phải là số nguyên.',
            'max_depth.min' => 'Số tầng phải từ 1 trở lên.',
            'max_depth.max' => 'Số tầng không được vượt quá 20.',
            'spillover_mode.in' => 'Chế độ spillover không hợp lệ.',
            'placement_mode.in' => 'Chế độ đặt vị trí không hợp lệ.',
            'capping_per_cycle.numeric' => 'Trần hoa hồng phải là số.',
            'capping_per_cycle.min' => 'Trần hoa hồng phải từ 0 trở lên.',
            'cycle_period.in' => 'Chu kỳ không hợp lệ.',
            'commissions.array' => 'Tỷ lệ hoa hồng phải là mảng.',
            'commissions.*.numeric' => 'Tỷ lệ hoa hồng phải là số.',
            'commissions.*.min' => 'Tỷ lệ hoa hồng phải từ 0 trở lên.',
            'commissions.*.max' => 'Tỷ lệ hoa hồng không được vượt quá 1.',
            'min_personal_volume.numeric' => 'Khối lượng cá nhân tối thiểu phải là số.',
            'min_personal_volume.min' => 'Khối lượng cá nhân tối thiểu phải từ 0 trở lên.',
            'active_order_days.integer' => 'Số ngày đơn hàng hoạt động phải là số nguyên.',
            'active_order_days.min' => 'Số ngày đơn hàng hoạt động phải từ 0 trở lên.',
            'kyc_required.boolean' => 'Yêu cầu KYC phải là true hoặc false.',
        ];
    }
}