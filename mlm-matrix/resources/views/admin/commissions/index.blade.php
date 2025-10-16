@extends('layouts.app')

@section('title', 'Quản lý Hoa hồng')

@push('styles')
<link href="{{ asset('css/admin/admin-common.css') }}" rel="stylesheet">
<link href="{{ asset('css/admin/commissions.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="admin-container">
    <div class="container-fluid">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @endif

        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0"><i class="fas fa-coins"></i> Quản lý Chính sách Hoa hồng</h2>
                    <p class="text-muted mb-0">Quản lý các mẫu hoa hồng cho hệ thống MLM</p>
                </div>
                <a href="{{ route('admin.commissions.create') }}" class="btn btn-gradient">
                    <i class="fas fa-plus"></i> Tạo chính sách mới
                </a>
            </div>
        </div>

        <div class="commission-grid">
            @forelse($commissions as $commission)
                @php
                    $data = json_decode($commission->value, true);
                @endphp
                
                <div class="commission-card fade-in-up">
                    <div class="commission-card-header">
                        <h4>
                            <i class="fas fa-coins"></i> 
                            {{ $data['name'] ?? 'Chưa đặt tên' }}
                        </h4>
                        <div class="commission-meta">
                            <span>
                                <i class="fas fa-info-circle"></i> 
                                {{ $data['description'] ?? 'Không có mô tả' }}
                            </span>
                            <span>
                                <i class="fas fa-layer-group"></i> 
                                {{ count($data['levels'] ?? []) }} tầng
                            </span>
                        </div>
                    </div>

                    <div class="commission-card-body">
                        <div class="commission-levels">
                            @if(isset($data['levels']) && is_array($data['levels']))
                                @foreach($data['levels'] as $index => $level)
                                <div class="commission-level">
                                    <div class="level-info">
                                        <div class="level-number">{{ $index + 1 }}</div>
                                        <div class="level-label">Tầng {{ $index + 1 }}</div>
                                    </div>
                                    <div class="level-percentage">
                                        {{ number_format($level['rate'] * 100, 1) }}<span class="percent-sign">%</span>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="commission-card-footer">
                        <a href="{{ route('admin.commissions.edit', $commission->id) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        <form action="{{ route('admin.commissions.destroy', $commission->id) }}" 
                              method="POST" 
                              style="display: inline-block;" 
                              onsubmit="return confirm('⚠️ Bạn có chắc chắn muốn xóa chính sách hoa hồng này?\n\nHành động này không thể hoàn tác!');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="commission-empty-state">
                    <i class="fas fa-coins"></i>
                    <h4>Chưa có chính sách hoa hồng nào</h4>
                    <p>Bắt đầu bằng cách tạo chính sách hoa hồng đầu tiên của bạn</p>
                    <a href="{{ route('admin.commissions.create') }}" class="btn btn-gradient">
                        <i class="fas fa-plus"></i> Tạo chính sách đầu tiên
                    </a>
                </div>
            @endforelse
        </div>

        @if($commissions->hasPages())
        <div class="mt-4">
            {{ $commissions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

