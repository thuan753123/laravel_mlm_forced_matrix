<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6"><?php echo e(__('ui.matrix_page.heading')); ?></h1>
                
                <?php if(auth()->guard()->check()): ?>
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
                                    <p class="text-sm font-medium text-gray-600">Tổng Thành Viên</p>
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
                                    <p class="text-sm font-medium text-gray-600">Cấp Độ</p>
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

                    <!-- Matrix Tree Visualization with D3.js -->
                    <div class="bg-white p-6 rounded-lg shadow mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold"><?php echo e(__('ui.matrix_page.visualization')); ?></h3>
                                <div id="visualization-info" class="text-sm text-gray-600 mt-1">
                                    <!-- Thông tin về visualization sẽ được cập nhật bởi JavaScript -->
                                </div>
                            </div>

                            <!-- D3.js Zoom Controls -->
                            <div class="zoom-controls flex items-center space-x-2 bg-gray-50 rounded-lg p-2">
                                <button id="zoom-out" class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded transition-colors" title="Zoom Out (Mouse wheel or Ctrl + -)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                    </svg>
                                </button>

                                <span id="zoom-level" class="text-sm font-medium text-gray-700 min-w-[60px] text-center">100%</span>

                                <button id="zoom-in" class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded transition-colors" title="Zoom In (Mouse wheel or Ctrl + +)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM12 10h.01M12 14h.01M12 18h.01"></path>
                                    </svg>
                                </button>

                                <div class="w-px h-6 bg-gray-300"></div>

                                <button id="center-tree" class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded transition-colors" title="Fit to Screen">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>

                                <button id="reset-view" class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded transition-colors" title="Reset View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- D3.js Tree Container -->
                        <div class="matrix-tree-container relative bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg border border-gray-200 overflow-hidden" style="min-height: 600px;">
                            <!-- Loading indicator -->
                            <div id="tree-loading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-90 z-20">
                                <div class="text-center">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-4"></div>
                                    <p class="text-gray-600">Loading matrix tree...</p>
                                </div>
                            </div>

                            <!-- Zoom Instructions -->
                            <div id="zoom-instructions" class="absolute top-3 left-3 bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-700 shadow-lg opacity-0 transition-all duration-300 pointer-events-none z-10 max-w-xs">
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <strong>Navigation Tips:</strong>
                                </div>
                                <div class="space-y-1">
                                    <div>• <strong>Zoom:</strong> Mouse wheel or buttons</div>
                                    <div>• <strong>Pan:</strong> Click and drag</div>
                                    <div>• <strong>Auto-center:</strong> Tree centers on load</div>
                                    <div>• <strong>Reset:</strong> Click reset button</div>
                                </div>
                            </div>

                            <!-- D3.js SVG Container -->
                            <svg id="matrix-tree-svg" class="w-full h-full" style="min-height: 600px;"></svg>

                            <!-- Hidden container for D3 data processing -->
                            <div id="tree-data-container" class="hidden"></div>
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
            
                <?php else: ?>
                    <div class="text-center py-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Vui lòng đăng nhập</h2>
                        <p class="text-lg text-gray-600 mb-8">Bạn cần đăng nhập để xem cây ma trận</p>
                        <a href="<?php echo e(route('login')); ?>" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <?php echo e(__('ui.auth.login')); ?>

                        </a>
                    </div>
                <?php endif; ?>

                <?php if(auth()->guard()->check()): ?>
                    <!-- Downline List Section for Large Datasets -->
                    <div class="bg-white p-6 rounded-lg shadow mt-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold">Danh sách Downline</h3>
                            <div class="text-sm text-gray-600">
                                <span id="downline-summary">Đang tải...</span>
                            </div>
                        </div>

                        <!-- Search and Filter Controls -->
                        <div class="flex flex-col sm:flex-row gap-4 mb-6">
                            <div class="flex-1">
                                <input type="text" id="downline-search" placeholder="Tìm kiếm theo tên, email hoặc mã giới thiệu..."
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div class="flex gap-2">
                                <select id="sort-by" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="position">Sắp xếp theo vị trí</option>
                                    <option value="users.fullname">Sắp xếp theo tên</option>
                                    <option value="users.email">Sắp xếp theo email</option>
                                    <option value="users.created_at">Sắp xếp theo ngày tham gia</option>
                                </select>
                                <select id="sort-order" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="asc">Tăng dần</option>
                                    <option value="desc">Giảm dần</option>
                                </select>
                                <select id="per-page" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="25">25 mỗi trang</option>
                                    <option value="50" selected>50 mỗi trang</option>
                                    <option value="100">100 mỗi trang</option>
                                </select>
                            </div>
                        </div>

                        <!-- Downline List Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vị trí</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avatar</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã GT</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tham gia</th>
                                    </tr>
                                </thead>
                                <tbody id="downline-tbody" class="bg-white divide-y divide-gray-200">
                                    <!-- Downline data sẽ được tải bởi JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Controls -->
                        <div id="pagination-controls" class="flex items-center justify-between mt-6">
                            <!-- Pagination sẽ được cập nhật bởi JavaScript -->
                        </div>

                        <!-- Loading indicator -->
                        <div id="downline-loading" class="text-center py-8 hidden">
                            <div class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-gray-600">Đang tải danh sách downline...</span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if(auth()->guard()->check()): ?>
<!-- Load D3.js from CDN -->
<script src="https://d3js.org/d3.v7.min.js"></script>

<script>
// D3.js Matrix Tree Implementation
let svg, g, zoom, treeData, currentTransform = { x: 0, y: 0, k: 1 };
let tooltip, nodeWidth = 140, nodeHeight = 80, linkDistance = 140;

document.addEventListener('DOMContentLoaded', function() {
    initializeD3Tree();
    loadMatrixData();
    // Note: loadDownlineList() is called in the event listeners below
});

async function loadMatrixData() {
    try {
        // Load matrix stats using web routes
        const statsResponse = await fetch('/matrix/stats');
        if (statsResponse.ok) {
            const statsData = await statsResponse.json();
            document.getElementById('total-downline').textContent = statsData.total_downline || 0;
            document.getElementById('direct-downline').textContent = statsData.direct_downline || 0;
            document.getElementById('current-depth').textContent = statsData.depth === 0 ? 'Root' : 'Direct Downline';
            document.getElementById('position').textContent = statsData.position || 0;
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

// Initialize D3.js tree
function initializeD3Tree() {
    const container = document.querySelector('.matrix-tree-container');
    const width = container.clientWidth;
    const height = container.clientHeight;

    // Create SVG
    svg = d3.select('#matrix-tree-svg')
        .attr('width', width)
        .attr('height', height);

    // Create main group for zoom/pan
    g = svg.append('g')
        .attr('class', 'main-group');

    // Create tooltip
    tooltip = d3.select('body').append('div')
        .attr('class', 'matrix-tooltip')
        .style('opacity', 0)
        .style('position', 'absolute')
        .style('background', 'rgba(0, 0, 0, 0.9)')
        .style('color', 'white')
        .style('padding', '8px 12px')
        .style('border-radius', '6px')
        .style('font-size', '12px')
        .style('pointer-events', 'none')
        .style('z-index', '1000');

    // Setup zoom behavior
    zoom = d3.zoom()
        .scaleExtent([0.1, 5])
        .on('zoom', function(event) {
            g.attr('transform', event.transform);
            currentTransform = event.transform;
            updateZoomLevel();
        });

    svg.call(zoom);
}

// Transform tree data to D3.js hierarchy format
function transformToD3Tree(treeData) {
    if (!treeData || !treeData.id) return null;

    // Transform a single node for D3.js hierarchy
    function transformNode(node) {
        if (!node) return null;

        return {
            id: node.id,
            name: node.user?.fullname || node.user?.email || 'Unknown',
            email: node.user?.email || '',
            referral_code: node.user?.referral_code || '',
            sponsor: node.sponsor,
            depth: node.depth || 0,
            position: node.position || 0,
            children: node.children ? node.children.map(transformNode).filter(child => child !== null) : []
        };
    }

    return transformNode(treeData);
}

// Render tree with D3.js
function renderD3Tree(data) {
    if (!data) {
        showEmptyState('No data available');
        return;
    }

    // Clear previous content
    g.selectAll('*').remove();

    // Transform data for D3 tree layout
    const transformedData = transformToD3Tree(data);
    if (!transformedData) {
        console.error('Failed to transform data for D3.js:', data);
        showEmptyState('Cannot process tree data format');
        return;
    }

    console.log('Transformed data for D3.js:', transformedData);

    const treeLayout = d3.tree()
        .nodeSize([nodeWidth * 1.5, linkDistance])
        .separation((a, b) => a.parent === b.parent ? 1.2 : 1.5);

    const root = d3.hierarchy(transformedData);
    treeLayout(root);

    // Create links (connections between nodes)
    g.selectAll('.link')
        .data(root.links())
        .enter().append('path')
        .attr('class', 'link')
        .attr('d', d3.linkVertical()
            .x(d => d.x)
            .y(d => d.y))
        .style('fill', 'none')
        .style('stroke', '#6366f1')
        .style('stroke-width', '2px')
        .style('opacity', '0.6')
        .style('stroke-linecap', 'round');

    // Create nodes
    const nodes = g.selectAll('.node')
        .data(root.descendants())
        .enter().append('g')
        .attr('class', d => `node ${d.children ? 'node--internal' : 'node--leaf'}`)
        .attr('transform', d => `translate(${d.x - nodeWidth/2},${d.y - nodeHeight/2})`)
        .style('cursor', 'pointer');

    // Add rectangles for nodes
    nodes.append('rect')
        .attr('width', nodeWidth)
        .attr('height', nodeHeight)
        .attr('x', 0)
        .attr('y', 0)
        .attr('rx', 8) // Rounded corners
        .style('fill', d => {
            if (d.data.depth === 0) return '#6366f1'; // Root - indigo
            if (d.data.depth === 1) return '#3b82f6'; // Level 1 (Direct Downline) - blue
            // Single level matrix - all other levels use the same color as level 1
            return '#3b82f6';
            return '#ef4444'; // Level 4+ - red
        })
        .style('stroke', '#fff')
        .style('stroke-width', '2px')
        .style('filter', 'drop-shadow(0 3px 6px rgba(0,0,0,0.15))');

    // Add user name text
    nodes.append('text')
        .attr('x', nodeWidth / 2)
        .attr('y', 20)
        .attr('text-anchor', 'middle')
        .style('fill', 'white')
        .style('font-size', '12px')
        .style('font-weight', 'bold')
        .text(d => truncateText(d.data.name, 15));

    // Add sponsor info
    nodes.append('text')
        .attr('x', nodeWidth / 2)
        .attr('y', 35)
        .attr('text-anchor', 'middle')
        .style('fill', 'rgba(255,255,255,0.9)')
        .style('font-size', '10px')
        .text(d => {
            const sponsorName = d.data.sponsor?.fullname || d.data.sponsor?.email || 'N/A';
            return `S: ${truncateText(sponsorName, 12)}`;
        });

    // Add level and position info
    nodes.append('text')
        .attr('x', nodeWidth / 2)
        .attr('y', 55)
        .attr('text-anchor', 'middle')
        .style('fill', 'rgba(255,255,255,0.8)')
        .style('font-size', '9px')
        .text(d => {
            if (d.data.depth === 0) return 'ROOT';
            return `P${d.data.position}`;
        });

    // Add referral code
    nodes.append('text')
        .attr('x', nodeWidth / 2)
        .attr('y', 70)
        .attr('text-anchor', 'middle')
        .style('fill', 'rgba(255,255,255,0.7)')
        .style('font-size', '8px')
        .text(d => truncateText(d.data.referral_code || '', 10));

    // Add hover effects and tooltips
    nodes
        .on('mouseover', function(event, d) {
            d3.select(this).select('rect')
                .transition()
                .duration(200)
                .style('filter', 'drop-shadow(0 6px 12px rgba(0,0,0,0.25))')
                .style('stroke-width', '3px');

            showTooltip(event, d);
        })
        .on('mouseout', function() {
            d3.select(this).select('rect')
                .transition()
                .duration(200)
                .style('filter', 'drop-shadow(0 3px 6px rgba(0,0,0,0.15))')
                .style('stroke-width', '2px');

            hideTooltip();
        })
        .on('click', function(event, d) {
            // Optional: add click interaction
            console.log('Node clicked:', d.data);
        });
}

// Truncate text helper function
function truncateText(text, maxLength) {
    if (!text) return '';
    return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
}

// Show tooltip on hover
function showTooltip(event, d) {
    const data = d.data;
    const sponsorInfo = data.sponsor ?
        `<br><strong>Sponsor:</strong> ${data.sponsor.fullname || data.sponsor.email || 'N/A'}` : '';

    tooltip
        .style('opacity', 1)
        .html(`
            <div style="font-weight: bold; margin-bottom: 4px; color: #fbbf24;">${data.name}</div>
            <div><strong>Email:</strong> ${data.email}</div>
            <div><strong>Referral Code:</strong> ${data.referral_code}</div>
            <div><strong>Level:</strong> ${data.depth === 0 ? 'Root' : 'Direct Downline'}</div>
            <div><strong>Position:</strong> ${data.position}</div>
            ${sponsorInfo}
        `)
        .style('left', (event.pageX + 15) + 'px')
        .style('top', (event.pageY - 15) + 'px');
}

// Hide tooltip
function hideTooltip() {
    tooltip.style('opacity', 0);
}

// Update zoom level display
function updateZoomLevel() {
    const zoomLevelElement = document.getElementById('zoom-level');
    if (zoomLevelElement) {
        zoomLevelElement.textContent = Math.round(currentTransform.k * 100) + '%';
    }
}

// Update visualization info display
function updateVisualizationInfo(data) {
    const infoElement = document.getElementById('visualization-info');
    if (!infoElement || !data.visualization) return;

    const visualization = data.visualization;
    const childrenInfo = visualization.children_info || {};

    let infoText = '';

    if (childrenInfo.has_more) {
        infoText = `Hiển thị ${childrenInfo.displayed}/${childrenInfo.total} downlines. Tổng cộng ${childrenInfo.total} downlines trực tiếp - xem tất cả trong danh sách bên dưới.`;
    } else if (childrenInfo.total > 0) {
        infoText = `${childrenInfo.total} downlines trực tiếp`;
    } else {
        infoText = 'Chưa có downlines';
    }

    infoElement.innerHTML = infoText;
}

// Center tree in the middle of the container
function centerTree() {
    if (!treeData) return;

    const bounds = g.node().getBBox();
    const fullWidth = bounds.width;
    const fullHeight = bounds.height;
    const midX = bounds.x + fullWidth / 2;
    const midY = bounds.y + fullHeight / 2;

    const container = document.querySelector('.matrix-tree-container');
    const width = container.clientWidth;
    const height = container.clientHeight;

    // For single level tree, use a scale that fits comfortably
    const scale = Math.min(width / fullWidth, height / fullHeight) * 0.8;
    const translateX = width / 2 - scale * midX;
    const translateY = height / 2 - scale * midY;

    // Apply transform immediately for better UX
    svg.call(zoom.transform,
        d3.zoomIdentity
            .translate(translateX, translateY)
            .scale(scale)
    );

    // Update current transform
    currentTransform = {
        x: translateX,
        y: translateY,
        k: scale
    };
    updateZoomLevel();
}

// Fit tree to screen
function fitToScreen() {
    if (!treeData) return;

    const bounds = g.node().getBBox();
    const fullWidth = bounds.width;
    const fullHeight = bounds.height;
    const midX = bounds.x + fullWidth / 2;
    const midY = bounds.y + fullHeight / 2;

    const container = document.querySelector('.matrix-tree-container');
    const width = container.clientWidth;
    const height = container.clientHeight;

    // Calculate scale to fit tree within container
    const scale = 0.8 * Math.min(width / fullWidth, height / fullHeight);
    const translateX = width / 2 - scale * midX;
    const translateY = height / 2 - scale * midY;

    svg.transition()
        .duration(750)
        .call(zoom.transform,
            d3.zoomIdentity
                .translate(translateX, translateY)
                .scale(scale)
        );
}

// Reset view
function resetView() {
    svg.transition()
        .duration(750)
        .call(zoom.transform, d3.zoomIdentity);
}

// Show empty state
function showEmptyState(message) {
    g.selectAll('*').remove();
    g.append('text')
        .attr('x', 0)
        .attr('y', 0)
        .attr('text-anchor', 'middle')
        .style('fill', '#6b7280')
        .style('font-size', '16px')
        .text(message);
}

// Load and render matrix tree
async function loadMatrixTree() {
    try {
        console.log('Loading matrix tree with D3.js...');
        const response = await fetch('/matrix/visualization?depth=1');

        if (response.ok) {
            const data = await response.json();
            console.log('Matrix data loaded:', data);
            console.log('Visualization data type:', typeof data.visualization);
            console.log('Visualization data keys:', data.visualization ? Object.keys(data.visualization) : 'null');

            // Hide loading indicator
            document.getElementById('tree-loading').style.display = 'none';

            if (data.visualization && data.visualization.id) {
                treeData = data.visualization;
                console.log('Tree data structure:', treeData);
                renderD3Tree(data.visualization);

                // Update visualization info
                updateVisualizationInfo(data);

                // Setup controls after rendering
                setupD3Controls();

                // Auto-center the tree for better first-time experience
                setTimeout(() => {
                    centerTree();
                }, 200);

                // Show instructions briefly
                setTimeout(() => {
                    document.getElementById('zoom-instructions').style.opacity = '1';
                    setTimeout(() => {
                        document.getElementById('zoom-instructions').style.opacity = '0';
                    }, 4000);
                }, 1000);
            } else {
                console.error('Visualization data is not a valid tree structure:', data.visualization);
                showEmptyState('Invalid tree data structure');
            }
        } else {
            showEmptyState('Failed to load matrix data');
        }
    } catch (error) {
        console.error('Error loading matrix tree:', error);
        showEmptyState('Error loading matrix data');
    }
}

// Setup D3.js specific controls
function setupD3Controls() {
    // Zoom in button
    document.getElementById('zoom-in').addEventListener('click', () => {
        svg.transition().duration(300).call(
            zoom.scaleBy, 1.5
        );
    });

    // Zoom out button
    document.getElementById('zoom-out').addEventListener('click', () => {
        svg.transition().duration(300).call(
            zoom.scaleBy, 1 / 1.5
        );
    });

    // Fit to screen button
    document.getElementById('center-tree').addEventListener('click', fitToScreen);

    // Reset view button
    document.getElementById('reset-view').addEventListener('click', resetView);

    // Update zoom level display
    updateZoomLevel();
}


async function loadUplineChain() {
    try {
        const response = await fetch('/matrix/me');
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

// Global variables for downline management
let currentDownlinePage = 1;
let currentDownlineFilters = {
    search: '',
    sort_by: 'position',
    sort_order: 'asc',
    per_page: 50
};

// Load downline list with pagination and filtering
async function loadDownlineList(page = 1, filters = {}) {
    try {
        // Show loading indicator
        document.getElementById('downline-loading').classList.remove('hidden');
        document.getElementById('downline-tbody').innerHTML = '';

        // Merge filters
        const queryParams = new URLSearchParams({
            page: page,
            ...currentDownlineFilters,
            ...filters
        });

        const response = await fetch(`/api/matrix/downline?${queryParams.toString()}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.ok) {
            const data = await response.json();

            // Update summary
            updateDownlineSummary(data);

            // Render table rows
            renderDownlineTable(data.downline);

            // Update pagination
            renderPagination(data.pagination);

            // Update current state
            currentDownlinePage = page;
            currentDownlineFilters = { ...currentDownlineFilters, ...filters };
        } else {
            throw new Error(`Failed to load downline data: ${response.status} ${response.statusText}`);
        }
    } catch (error) {
        console.error('Error loading downline list:', error);
        document.getElementById('downline-tbody').innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-red-500">
                    Lỗi tải danh sách downline. Vui lòng thử lại.
                </td>
            </tr>
        `;
    } finally {
        document.getElementById('downline-loading').classList.add('hidden');
    }
}

// Update downline summary
function updateDownlineSummary(data) {
    const summary = document.getElementById('downline-summary');
    const total = data.pagination?.total || 0;
    const active = data.summary?.active_downlines || 0;

    summary.innerHTML = `
        Tổng: <strong>${total}</strong> downline
        (${active} đang hoạt động)
    `;
}

// Render downline table rows
function renderDownlineTable(downlines) {
    const tbody = document.getElementById('downline-tbody');

    if (!downlines || downlines.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                    Không có downline nào phù hợp với tiêu chí tìm kiếm.
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = downlines.map(downline => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${downline.position}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-sm font-medium text-indigo-600">
                    ${downline.avatar}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${downline.fullname}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${downline.email}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${downline.referral_code}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                    downline.is_active
                        ? 'bg-green-100 text-green-800'
                        : 'bg-red-100 text-red-800'
                }">
                    ${downline.is_active ? 'Hoạt động' : 'Không hoạt động'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${new Date(downline.created_at).toLocaleDateString('vi-VN')}
            </td>
        </tr>
    `).join('');
}

// Render pagination controls
function renderPagination(pagination) {
    const container = document.getElementById('pagination-controls');

    if (!pagination || pagination.total <= pagination.per_page) {
        container.innerHTML = '';
        return;
    }

    const { current_page, last_page, total, from, to } = pagination;

    container.innerHTML = `
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-700">
                Hiển thị ${from}-${to} của ${total} kết quả
            </span>
        </div>
        <div class="flex items-center space-x-1">
            <button
                onclick="loadDownlineList(${current_page - 1})"
                ${current_page <= 1 ? 'disabled' : ''}
                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 ${current_page <= 1 ? 'opacity-50 cursor-not-allowed' : ''}"
            >
                Trước
            </button>

            ${generatePageNumbers(current_page, last_page)}

            <button
                onclick="loadDownlineList(${current_page + 1})"
                ${current_page >= last_page ? 'disabled' : ''}
                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 ${current_page >= last_page ? 'opacity-50 cursor-not-allowed' : ''}"
            >
                Tiếp
            </button>
        </div>
    `;
}

// Generate page number buttons
function generatePageNumbers(current, last) {
    let buttons = [];

    // Always show first page
    if (current > 1) {
        buttons.push(`<button onclick="loadDownlineList(1)" class="px-3 py-2 text-sm font-medium text-indigo-600 bg-white border border-gray-300 rounded-md hover:bg-indigo-50">1</button>`);
    }

    // Show ellipsis if needed
    if (current > 3) {
        buttons.push('<span class="px-3 py-2 text-sm font-medium text-gray-700">...</span>');
    }

    // Show current page and adjacent pages
    for (let i = Math.max(2, current - 1); i <= Math.min(last - 1, current + 1); i++) {
        buttons.push(`
            <button
                onclick="loadDownlineList(${i})"
                class="px-3 py-2 text-sm font-medium ${i === current ? 'text-white bg-indigo-600 border border-indigo-600' : 'text-indigo-600 bg-white border border-gray-300 hover:bg-indigo-50'} rounded-md"
            >
                ${i}
            </button>
        `);
    }

    // Show ellipsis if needed
    if (current < last - 2) {
        buttons.push('<span class="px-3 py-2 text-sm font-medium text-gray-700">...</span>');
    }

    // Always show last page
    if (current < last) {
        buttons.push(`<button onclick="loadDownlineList(${last})" class="px-3 py-2 text-sm font-medium text-indigo-600 bg-white border border-gray-300 rounded-md hover:bg-indigo-50">${last}</button>`);
    }

    return buttons.join('');
}

// Event listeners for filters
document.addEventListener('DOMContentLoaded', function() {
    // Search input
    const searchInput = document.getElementById('downline-search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadDownlineList(1, { search: this.value });
            }, 500); // Debounce 500ms
        });
    }

    // Sort controls
    const sortBy = document.getElementById('sort-by');
    const sortOrder = document.getElementById('sort-order');
    const perPage = document.getElementById('per-page');

    if (sortBy) {
        sortBy.addEventListener('change', function() {
            loadDownlineList(1, { sort_by: this.value });
        });
    }

    if (sortOrder) {
        sortOrder.addEventListener('change', function() {
            loadDownlineList(1, { sort_order: this.value });
        });
    }

    if (perPage) {
        perPage.addEventListener('change', function() {
            loadDownlineList(1, { per_page: parseInt(this.value) });
        });
    }

    // Initial load
    loadDownlineList();
});
</script>

<style>
/* D3.js Matrix Tree Styles */
.matrix-tree-container {
    position: relative;
    min-height: 600px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

#matrix-tree-svg {
    width: 100%;
    height: 100%;
    min-height: 600px;
}

#matrix-tree-svg .link {
    fill: none;
    stroke: #6366f1;
    stroke-width: 2px;
    opacity: 0.7;
    stroke-linecap: round;
}

#matrix-tree-svg .node rect {
    cursor: pointer;
    transition: all 0.2s ease;
}

#matrix-tree-svg .node text {
    pointer-events: none;
}

/* D3.js Tooltip */
.matrix-tooltip {
    position: absolute;
    background: rgba(0, 0, 0, 0.9);
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 12px;
    pointer-events: none;
    z-index: 1000;
    max-width: 280px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.matrix-node {
    transition: all 0.3s ease;
    cursor: pointer;
    z-index: 5;
}

.matrix-node:hover {
    transform: scale(1.05);
    z-index: 15;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.matrix-node.current-user {
    animation: pulse 2s infinite;
    z-index: 20;
    box-shadow: 0 15px 35px rgba(99, 102, 241, 0.3);
}

.matrix-node:hover .node-card {
    box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.15), 0 10px 15px -5px rgba(0, 0, 0, 0.1);
}

/* Legend */
.legend {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: white;
    padding: 1rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    font-size: 0.75rem;
}

.legend-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 0.5rem;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Node Card Styles */
.node-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    padding: 1rem;
    min-width: 140px;
    max-width: 180px;
    text-align: center;
    border: 3px solid;
    position: relative;
    transition: all 0.3s ease;
}

.root-node {
    border-color: #6366f1;
    background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
}

.child-node {
    border-color: #3b82f6;
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
}

.node-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.75rem;
    border: 3px solid white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.avatar-text {
    color: white;
    font-weight: bold;
    font-size: 1.25rem;
}

.node-info {
    text-align: center;
}

.node-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.node-sponsor {
    margin-bottom: 0.5rem;
    text-align: center;
}

.sponsor-label {
    color: #6b7280;
    font-size: 0.625rem;
    display: block;
    margin-bottom: 0.25rem;
}

.sponsor-name {
    color: #8b5cf6;
    font-weight: 600;
    font-size: 0.75rem;
    background: rgba(139, 92, 246, 0.1);
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    display: inline-block;
}

.node-details {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    color: #6b7280;
}

.node-level, .node-position {
    background: rgba(255, 255, 255, 0.7);
    padding: 0.125rem 0.25rem;
    border-radius: 4px;
    font-weight: 500;
}

/* Level-based styling */
.level-0 .node-card {
    border-color: #6366f1;
    background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
}

.level-0 .node-avatar {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
}

.level-1 .node-card {
    border-color: #3b82f6;
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
}

.level-1 .node-avatar {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.level-2 .node-card {
    border-color: #10b981;
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
}

.level-2 .node-avatar {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.level-3 .node-card {
    border-color: #f59e0b;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
}

.level-3 .node-avatar {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.level-4 .node-card {
    border-color: #ef4444;
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
}

.level-4 .node-avatar {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

/* SVG Lines */
#tree-svg line {
    stroke-linecap: round;
    stroke-linejoin: round;
}

/* Zoom controls responsive */
@media (max-width: 768px) {
    .zoom-controls {
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .zoom-controls button {
        padding: 0.5rem;
    }

    .zoom-controls span {
        font-size: 0.75rem;
        min-width: 50px;
    }
}

/* Responsive design */
@media (max-width: 768px) {
    #tree-container {
        padding: 1.5rem;
        overflow-x: auto;
    }

    .node-card {
        min-width: 120px;
        max-width: 140px;
        padding: 0.75rem;
    }

    .node-avatar {
        width: 40px;
        height: 40px;
    }

    .avatar-text {
        font-size: 1rem;
    }

    .node-name {
        font-size: 0.75rem;
    }

    .node-sponsor {
        margin-bottom: 0.25rem;
    }

    .sponsor-label {
        font-size: 0.55rem;
    }

    .sponsor-name {
        font-size: 0.65rem;
        padding: 0.15rem 0.3rem;
    }

    .node-details {
        font-size: 0.65rem;
    }

    /* Hide zoom controls text on very small screens */
    @media (max-width: 480px) {
        .zoom-controls {
            padding: 0.25rem;
        }

        .zoom-controls button {
            padding: 0.4rem;
        }

        .zoom-controls span {
            display: none;
        }
    }
}

/* Loading state */
.loading-spinner {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 200px;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6b7280;
}

.empty-state svg {
    width: 64px;
    height: 64px;
    margin: 0 auto 1rem;
    opacity: 0.5;
}
</style>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/kentpc/Documents/GitHub/laravel_mlm_forced_matrix/mlm-matrix/resources/views/matrix/index.blade.php ENDPATH**/ ?>