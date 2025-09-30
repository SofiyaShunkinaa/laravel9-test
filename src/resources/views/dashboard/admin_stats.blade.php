@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Заголовок страницы -->
    <header class="mb-10">
        <h1 class="text-3xl font-bold text-gray-900">Statistics & Analytics</h1>
        <p class="mt-2 text-gray-600">Comprehensive overview of your platform's performance</p>
    </header>

    <section class="mb-12">
    <header class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
            <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg mr-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            Posts Analytics
        </h2>
        <p class="mt-2 text-gray-600 ml-13">Track your content performance and engagement metrics</p>
    </header>
    
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <!-- Заголовок и контролы -->
        <div class="border-b border-gray-100 px-8 py-6 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-4 lg:mb-0">
                    <h3 class="text-lg font-semibold text-gray-900">Posts Creation Timeline</h3>
                    <p class="text-sm text-gray-600 mt-1">Monitor your publishing activity over time</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div>
                        <label for="chart-period" class="block text-sm font-medium text-gray-700 mb-1">Time Period</label>
                        <div class="relative">
                            <select id="chart-period" class="appearance-none bg-white border border-gray-300 rounded-xl px-4 py-2 pr-8 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                                <option value="day">Daily View</option>
                                <option value="week">Weekly View</option>
                                <option value="month">Monthly View</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- График -->
        <div class="px-6 py-4">
            <div class="bg-white rounded-lg p-3 border border-gray-200">
                <canvas id="postsChart" height="120"></canvas>
            </div>
        </div>

        <!-- Статистические карточки -->
        <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white bg-opacity-20 rounded-lg p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-1m6-4h.01M12 8h.01"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Average Comments per Post</p>
                    <p class="text-3xl font-bold mb-2" id="avgComments">—</p>
                    <p class="text-blue-200 text-xs">Engagement rate across all published content</p>
                </div>

                <!-- Посты по статусам -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Content Status
                        </h3>
                        <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2 py-1 rounded-full">Overview</span>
                    </div>
                    <ul id="postStatusCounts" class="space-y-3"></ul>
                </div>

                <!-- Топ комментируемых постов -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            Top Performing
                        </h3>
                        <span class="bg-purple-100 text-purple-600 text-xs font-medium px-2 py-1 rounded-full">Top 5</span>
                    </div>
                    <ul id="topPosts" class="space-y-3"></ul>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Статистика комментариев -->
    <section class="mb-12">
        <header class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                Comments Statistics
            </h2>
        </header>
        
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-medium text-gray-700 mb-3">Comments by Hour</h3>
                    <canvas id="commentsByHourChart"></canvas>
                </div>
                <div>
                    <h3 class="font-medium text-gray-700 mb-3">Comments by Weekday</h3>
                    <canvas id="commentsByWeekdayChart"></canvas>
                </div>
            </div>
            <div class="bg-green-50 rounded-lg p-4 max-w-xs">
                <p class="text-sm text-green-700 font-medium">Total comments</p>
                <p class="text-2xl font-bold text-green-900" id="totalComments">—</p>
            </div>
        </div>
    </section>

    <!-- Статистика пользователей -->
    <section class="mb-12">
        <header class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                Users Statistics
            </h2>
        </header>
        
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-purple-50 rounded-lg p-4">
                    <h3 class="font-semibold text-purple-800 mb-3">Users by Role</h3>
                    <ul id="countByRoles" class="space-y-2"></ul>
                </div>
                <div class="bg-pink-50 rounded-lg p-4">
                    <h3 class="font-semibold text-pink-800 mb-3">Top 5 Authors by Posts</h3>
                    <ul id="topAuthors" class="space-y-2"></ul>
                </div>
                <div class="bg-amber-50 rounded-lg p-4">
                    <h3 class="font-semibold text-amber-800 mb-3">Top 5 Commenters</h3>
                    <ul id="topCommenters" class="space-y-2"></ul>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const token = '{{ csrf_token() }}';
    const headers = { 'X-CSRF-TOKEN': token };

    let postsChart = null;

    function loadPostsStats(period = 'day') {
    fetch(`/api/dashboard/stats/posts?period=${period}`, { headers })
        .then(res => res.json())
        .then(data => {
            console.log(`Loaded data for period: ${period}`, data);

            const labels = data.posts_by_period?.map(d => d.label) ?? [];
            const counts = data.posts_by_period?.map(d => d.total) ?? [];

            if (labels.length === 0 || counts.length === 0) {
                console.warn('No data available for this period');
                return;
            }

            const ctx = document.getElementById('postsChart').getContext('2d');
            if (postsChart) postsChart.destroy();

            postsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: `Posts by ${period}`,
                        data: counts,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            document.getElementById('avgComments').textContent = data.avg_comments?.toFixed(2) ?? '0.00';

            const topPosts = document.getElementById('topPosts');
            topPosts.innerHTML = '';
            data.top5_most_commented.forEach(post => {
                topPosts.innerHTML += `
                    <li class="flex justify-between items-center bg-white p-2 rounded border">
                        <span class="truncate flex-1 mr-2">${post.title}</span>
                        <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-1 rounded">${post.comments_count}</span>
                    </li>`;
            });

            const postStatusCounts = document.getElementById('postStatusCounts');
            postStatusCounts.innerHTML = '';
            const statusLabels = { draft: 'Draft', published: 'Published' };
            for (const [status, count] of Object.entries(data.total_by_status)) {
                postStatusCounts.innerHTML += `
                    <li class="flex justify-between items-center bg-white p-2 rounded border">
                        <span class="capitalize text-sm">${statusLabels[status] ?? status}</span>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">${count}</span>
                    </li>`;
            }
        })
        .catch(err => {
            console.error('Failed to load posts stats:', err);
        });
}

    document.getElementById('chart-period').addEventListener('change', function () {
        loadPostsStats(this.value);
    });

    // Загрузка по умолчанию
    loadPostsStats('day');

    // ------------------------- COMMENTS -------------------------
    fetch('/api/dashboard/stats/comments', { headers })
        .then(res => res.json())
        .then(data => {
            document.getElementById('totalComments').textContent = data.total_comments;

            const ctxHour = document.getElementById('commentsByHourChart').getContext('2d');
            new Chart(ctxHour, {
                type: 'bar',
                data: {
                    labels: data.activity_by_hour.map(d => d.hour + ':00'),
                    datasets: [{ 
                        label: 'Comments by hour', 
                        data: data.activity_by_hour.map(d => d.total), 
                        backgroundColor: '#10b981',
                        borderColor: '#059669',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            const ctxWeekday = document.getElementById('commentsByWeekdayChart').getContext('2d');
            new Chart(ctxWeekday, {
                type: 'bar',
                data: {
                    labels: data.activity_by_weekday.map(d => ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][d.weekday]),
                    datasets: [{ 
                        label: 'Comments by weekday', 
                        data: data.activity_by_weekday.map(d => d.total), 
                        backgroundColor: '#f59e0b',
                        borderColor: '#d97706',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });

    // ------------------------- USERS -------------------------
    fetch('/api/dashboard/stats/users', { headers })
        .then(res => res.json())
        .then(data => {
            const rolesList = document.getElementById('countByRoles');
            rolesList.innerHTML = '';
            Object.entries(data.count_by_roles).forEach(([role, count]) => {
                rolesList.innerHTML += `
                    <li class="flex justify-between items-center bg-white p-2 rounded border">
                        <span class="capitalize">${role}</span>
                        <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2 py-1 rounded">${count}</span>
                    </li>`;
            });

            const topAuthors = document.getElementById('topAuthors');
            topAuthors.innerHTML = '';
            data.top5_authors.forEach(user => {
                topAuthors.innerHTML += `
                    <li class="flex justify-between items-center bg-white p-2 rounded border">
                        <span class="truncate flex-1 mr-2">${user.name}</span>
                        <span class="bg-pink-100 text-pink-800 text-xs font-medium px-2 py-1 rounded">${user.posts_count}</span>
                    </li>`;
            });

            const topCommenters = document.getElementById('topCommenters');
            topCommenters.innerHTML = '';
            data.top5_commenters.forEach(user => {
                topCommenters.innerHTML += `
                    <li class="flex justify-between items-center bg-white p-2 rounded border">
                        <span class="truncate flex-1 mr-2">${user.name}</span>
                        <span class="bg-amber-100 text-amber-800 text-xs font-medium px-2 py-1 rounded">${user.comments_count}</span>
                    </li>`;
            });
        });
});
</script>

@endsection