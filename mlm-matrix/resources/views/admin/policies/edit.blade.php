@extends('layouts.app')

@section('title', 'Chỉnh sửa Chính sách')

@push('styles')
<link href="{{ asset('css/admin/admin-common.css') }}" rel="stylesheet">
<link href="{{ asset('css/admin/policies.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="admin-container">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="policy-form fade-in-up">
                    <div class="form-header">
                        <h4><i class="fas fa-edit"></i> Chỉnh sửa Chính sách</h4>
                    </div>
                    <form action="{{ route('admin.policies.update', $policy->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label>Tên chính sách</label>
                            <input type="text" class="form-control" value="{{ str_replace('policy_', '', $policy->key) }}" disabled>
                            <small class="form-text text-muted">Không thể thay đổi tên chính sách</small>
                        </div>

                        <div class="form-group">
                            <label for="type">Loại dữ liệu *</label>
                            <select class="form-control @error('type') is-invalid @enderror" 
                                    id="type" name="type" required>
                                <option value="string" {{ $policy->type == 'string' ? 'selected' : '' }}>String</option>
                                <option value="integer" {{ $policy->type == 'integer' ? 'selected' : '' }}>Integer</option>
                                <option value="boolean" {{ $policy->type == 'boolean' ? 'selected' : '' }}>Boolean</option>
                                <option value="json" {{ $policy->type == 'json' ? 'selected' : '' }}>JSON</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="value">Giá trị *</label>
                            <textarea class="form-control @error('value') is-invalid @enderror" 
                                      id="value" name="value" rows="4" required>{{ old('value', $policy->value) }}</textarea>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="policy-actions">
                            <button type="submit" class="btn btn-gradient">
                                <i class="fas fa-save"></i> Cập nhật chính sách
                            </button>
                            <a href="{{ route('admin.policies.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

