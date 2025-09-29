@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Заголовок страницы -->
    <header class="mb-10">
        <h1 class="text-3xl font-bold text-gray-900">Statistics & Analytics</h1>
        <p class="mt-2 text-gray-600">Comprehensive overview of your platform's performance</p>
    </header>

    <!-- Статистика постов -->
    <section class="mb-12">
        <header class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Posts Statistics
            </h2>
        </header>
        
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <canvas id="postsChart" class="mb-6"></canvas>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-blue-700 font-medium">Average comments per post</p>
                    <p class="text-2xl font-bold text-blue-900" id="avgComments">—</p>
                </div>
                <div class="bg-indigo-50 rounded-lg p-4">
                    <h3 class="font-semibold text-indigo-800 mb-2">Top 5 Most Commented Posts</h3>
                    <ul id="topPosts" class="space-y-2"></ul>
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

    // --- Posts ---
    fetch('/api/dashboard/stats/posts', { headers })
        .then(res => res.json())
        .then(data => {
            const ctx = document.getElementById('postsChart').getContext('2d');
            const labels = data.total_by_period.map(d => d.date);
            const counts = data.total_by_period.map(d => d.total);
            new Chart(ctx, {
                type: 'line',
                data: { 
                    labels, 
                    datasets: [{ 
                        label: 'Posts by day', 
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

            document.getElementById('avgComments').textContent = data.avg_comments.toFixed(2);
            const topPosts = document.getElementById('topPosts');
            topPosts.innerHTML = '';
            data.top5_most_commented.forEach(post => {
                topPosts.innerHTML += `
                    <li class="flex justify-between items-center bg-white p-2 rounded border">
                        <span class="truncate flex-1 mr-2">${post.title}</span>
                        <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-1 rounded">${post.comments_count}</span>
                    </li>`;
            });
        });

    // --- Comments ---
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
                    labels: data.activity_by_weekday.map(d => ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][d.weekday-1]),
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

    // --- Users ---
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