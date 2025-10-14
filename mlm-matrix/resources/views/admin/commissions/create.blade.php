@extends('layouts.app')

@section('title', 'Tạo Chính sách Hoa hồng')

@push('styles')
<link href="{{ asset('css/admin/admin-common.css') }}" rel="stylesheet">
<link href="{{ asset('css/admin/commissions.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="admin-container">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="placeholder-content fade-in-up">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <p>
                            Chức năng này đang được phát triển. Vui lòng sử dụng 
                            <a href="{{ route('admin.config.index') }}">trang cấu hình MLM</a> 
                            để thiết lập hoa hồng.
                        </p>
                    </div>

                    <div class="d-flex gap-3 justify-content-center">
                        <a href="{{ route('admin.config.index') }}" class="btn btn-gradient">
                            <i class="fas fa-cog"></i> Đến trang cấu hình MLM
                        </a>
                        <a href="{{ route('admin.commissions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

