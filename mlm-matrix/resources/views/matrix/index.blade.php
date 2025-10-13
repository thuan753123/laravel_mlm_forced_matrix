@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">{{ __('ui.matrix_page.heading') }}</h1>
                
                @auth
                    <!-- Matrix Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Tổng Downline</p>
                                    <p class="text-2xl font-semibold text-gray-900" id="total-downline">-</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Downline Trực Tiếp</p>
                                    <p class="text-2xl font-semibold text-gray-900" id="direct-downline">-</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-yellow-100 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Tầng Hiện Tại</p>
                                    <p class="text-2xl font-semibold text-gray-900" id="current-depth">-</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Vị Trí</p>
                                    <p class="text-2xl font-semibold text-gray-900" id="position">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Matrix Tree Visualization -->
                    <div class="bg-white p-6 rounded-lg shadow mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ __('ui.matrix_page.visualization') }}</h3>
                        <div id="matrix-tree" class="min-h-96 flex items-center justify-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                        </div>
                    </div>

                    <!-- Upline Chain -->
                    <div class="bg-white p-6 rounded-lg shadow mb-8">
                        <h3 class="text-lg font-semibold mb-4">Chuỗi Upline</h3>
                        <div id="upline-chain" class="space-y-2">
                            <div class="flex items-center justify-center py-4">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Downline List -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4">Danh Sách Downline</h3>
                        <div id="downline-list" class="space-y-2">
                            <div class="flex items-center justify-center py-4">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Vui lòng đăng nhập</h2>
                        <p class="text-lg text-gray-600 mb-8">Bạn cần đăng nhập để xem cây ma trận</p>
                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('ui.auth.login') }}
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

@auth
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadMatrixData();
});

async function loadMatrixData() {
    try {
        // Load matrix stats
        const statsResponse = await fetch('/api/matrix/stats');
        if (statsResponse.ok) {
            const statsData = await statsResponse.json();
            document.getElementById('total-downline').textContent = statsData.user_stats.total_downline || 0;
            document.getElementById('direct-downline').textContent = statsData.user_stats.direct_downline || 0;
            document.getElementById('current-depth').textContent = statsData.user_stats.depth || 0;
            document.getElementById('position').textContent = statsData.user_stats.position || 0;
        }

        // Load matrix tree
        loadMatrixTree();
        
        // Load upline chain
        loadUplineChain();
        
        // Load downline list
        loadDownlineList();
    } catch (error) {
        console.error('Error loading matrix data:', error);
    }
}

async function loadMatrixTree() {
    try {
        const response = await fetch('/api/matrix/visualization');
        if (response.ok) {
            const data = await response.json();
            renderMatrixTree(data.visualization);
        }
    } catch (error) {
        console.error('Error loading matrix tree:', error);
        document.getElementById('matrix-tree').innerHTML = '<p class="text-red-500">Lỗi tải cây ma trận</p>';
    }
}

function renderMatrixTree(node, level = 0) {
    const container = document.getElementById('matrix-tree');
    
    if (!node) {
        container.innerHTML = '<p class="text-gray-500">Không có dữ liệu ma trận</p>';
        return;
    }
    
    const nodeElement = document.createElement('div');
    nodeElement.className = `matrix-node level-${level} ${level === 0 ? 'current-user' : ''}`;
    
    nodeElement.innerHTML = `
        <div class="bg-${level === 0 ? 'indigo' : 'blue'}-100 p-4 rounded-lg shadow-sm border-2 border-${level === 0 ? 'indigo' : 'blue'}-300">
            <div class="text-center">
                <div class="font-semibold text-${level === 0 ? 'indigo' : 'blue'}-800">${node.user.fullname || node.user.email}</div>
                <div class="text-sm text-${level === 0 ? 'indigo' : 'blue'}-600">Tầng ${node.depth}</div>
                <div class="text-xs text-${level === 0 ? 'indigo' : 'blue'}-500">Vị trí ${node.position}</div>
            </div>
        </div>
    `;
    
    if (node.children && node.children.length > 0) {
        const childrenContainer = document.createElement('div');
        childrenContainer.className = 'children-container mt-4 flex justify-center space-x-4';
        
        node.children.forEach((child, index) => {
            if (child) {
                const childElement = renderMatrixTree(child, level + 1);
                childrenContainer.appendChild(childElement);
            }
        });
        
        nodeElement.appendChild(childrenContainer);
    }
    
    return nodeElement;
}

async function loadUplineChain() {
    try {
        const response = await fetch('/api/matrix/me');
        if (response.ok) {
            const data = await response.json();
            const uplineContainer = document.getElementById('upline-chain');
            
            if (data.upline && data.upline.length > 0) {
                uplineContainer.innerHTML = data.upline.map((upline, index) => `
                    <div class="flex items-center justify-between py-2 px-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-semibold text-indigo-600">${index + 1}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">${upline.fullname || upline.email}</p>
                                <p class="text-sm text-gray-500">${upline.referral_code}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Tầng ${index + 1}</p>
                        </div>
                    </div>
                `).join('');
            } else {
                uplineContainer.innerHTML = '<p class="text-gray-500 text-center py-4">Không có upline</p>';
            }
        }
    } catch (error) {
        console.error('Error loading upline chain:', error);
        document.getElementById('upline-chain').innerHTML = '<p class="text-red-500 text-center py-4">Lỗi tải upline</p>';
    }
}

async function loadDownlineList() {
    try {
        const response = await fetch('/api/matrix/downline?depth=3');
        if (response.ok) {
            const data = await response.json();
            const downlineContainer = document.getElementById('downline-list');
            
            if (data.downline && data.downline.length > 0) {
                downlineContainer.innerHTML = data.downline.map((member, index) => `
                    <div class="flex items-center justify-between py-2 px-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-semibold text-green-600">${index + 1}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">${member.fullname || member.email}</p>
                                <p class="text-sm text-gray-500">${member.referral_code}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Thành viên</p>
                        </div>
                    </div>
                `).join('');
            } else {
                downlineContainer.innerHTML = '<p class="text-gray-500 text-center py-4">Chưa có downline</p>';
            }
        }
    } catch (error) {
        console.error('Error loading downline list:', error);
        document.getElementById('downline-list').innerHTML = '<p class="text-red-500 text-center py-4">Lỗi tải downline</p>';
    }
}
</script>

<style>
.matrix-node {
    display: inline-block;
    margin: 0.5rem;
}

.matrix-node.current-user {
    transform: scale(1.1);
}

.children-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1rem;
}

.level-0 .matrix-node {
    background-color: #e0e7ff;
    border-color: #6366f1;
}

.level-1 .matrix-node {
    background-color: #dbeafe;
    border-color: #3b82f6;
}

.level-2 .matrix-node {
    background-color: #d1fae5;
    border-color: #10b981;
}
</style>
@endauth
@endsection