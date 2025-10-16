@extends('layouts.app')

@section('title', 'Quản lý Chính sách')

@push('styles')
<link href="{{ asset('css/admin/admin-common.css') }}" rel="stylesheet">
<link href="{{ asset('css/admin/policies.css') }}" rel="stylesheet">
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
                    <h2 class="mb-0"><i class="fas fa-file-contract"></i> Quản lý Chính sách</h2>
                    <p class="text-muted mb-0">Quản lý tất cả chính sách hệ thống</p>
                </div>
                <a href="{{ route('admin.policies.create') }}" class="btn btn-gradient">
                    <i class="fas fa-plus"></i> Tạo chính sách mới
                </a>
            </div>
        </div>

        <div class="table-card">
            <div class="policy-table-wrapper">
                <table class="table table-hover policy-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên chính sách</th>
                            <th>Giá trị</th>
                            <th>Loại</th>
                            <th>Cập nhật</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($policies as $policy)
                        <tr class="fade-in-up">
                            <td><strong>#{{ $policy->id }}</strong></td>
                            <td>
                                <span class="policy-key">{{ str_replace('policy_', '', $policy->key) }}</span>
                            </td>
                            <td>
                                <span class="policy-value">{{ \Illuminate\Support\Str::limit($policy->value, 50) }}</span>
                            </td>
                            <td>
                                <span class="type-badge {{ $policy->type }}">{{ $policy->type }}</span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="far fa-clock"></i> {{ $policy->updated_at->diffForHumans() }}
                                </small>
                            </td>
                            <td class="text-center">
                                <div class="policy-actions">
                                    <a href="{{ route('admin.policies.edit', $policy->id) }}" 
                                       class="btn btn-sm btn-info shadow-hover" 
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.policies.destroy', $policy->id) }}" 
                                          method="POST" 
                                          class="delete-form" 
                                          onsubmit="return confirm('⚠️ Bạn có chắc chắn muốn xóa chính sách này?\n\nHành động này không thể hoàn tác!');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger shadow-hover" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h5>Chưa có chính sách nào</h5>
                                    <p>Bắt đầu bằng cách tạo chính sách đầu tiên của bạn</p>
                                    <a href="{{ route('admin.policies.create') }}" class="btn btn-gradient">
                                        <i class="fas fa-plus"></i> Tạo chính sách đầu tiên
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($policies->hasPages())
            <div class="mt-4">
                {{ $policies->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

