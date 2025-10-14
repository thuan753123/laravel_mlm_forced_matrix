@extends('layouts.app')

@section('title', 'Tạo Chính sách mới')

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
                        <h4><i class="fas fa-plus"></i> Tạo Chính sách mới</h4>
                    </div>
                    
                    <form action="{{ route('admin.policies.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">Tên chính sách <span class="required">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type">Loại dữ liệu *</label>
                            <select class="form-control @error('type') is-invalid @enderror" 
                                    id="type" name="type" required>
                                <option value="string" {{ old('type') == 'string' ? 'selected' : '' }}>String</option>
                                <option value="integer" {{ old('type') == 'integer' ? 'selected' : '' }}>Integer</option>
                                <option value="boolean" {{ old('type') == 'boolean' ? 'selected' : '' }}>Boolean</option>
                                <option value="json" {{ old('type') == 'json' ? 'selected' : '' }}>JSON</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="value">Giá trị *</label>
                            <textarea class="form-control @error('value') is-invalid @enderror" 
                                      id="value" name="value" rows="4" required>{{ old('value') }}</textarea>
                            <small class="form-text text-muted">Nhập giá trị phù hợp với loại dữ liệu đã chọn</small>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="policy-actions">
                            <button type="submit" class="btn btn-gradient">
                                <i class="fas fa-save"></i> Lưu chính sách
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

