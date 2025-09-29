@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Statistics & Analytics</h1>

    <!-- Posts statistics -->
    <div class="mb-10">
        <h2 class="text-xl font-semibold mb-4">Posts</h2>
        <canvas id="postsChart" class="mb-4"></canvas>
        <p>Average comments per post: <span id="avgComments">—</span></p>

        <h3 class="mt-4 font-semibold">Top 5 most commented posts</h3>
        <ul id="topPosts" class="list-disc pl-6"></ul>
    </div>

    <!-- Comments statistics -->
    <div class="mb-10">
        <h2 class="text-xl font-semibold mb-4">Comments</h2>
        <canvas id="commentsByHourChart" class="mb-4"></canvas>
        <canvas id="commentsByWeekdayChart" class="mb-4"></canvas>
        <p>Total comments: <span id="totalComments">—</span></p>
    </div>

    <!-- Users statistics -->
    <div class="mb-10">
        <h2 class="text-xl font-semibold mb-4">Users</h2>
        <ul id="countByRoles" class="list-disc pl-6 mb-4"></ul>

        <h3 class="mt-4 font-semibold">Top 5 authors by posts</h3>
        <ul id="topAuthors" class="list-disc pl-6"></ul>

        <h3 class="mt-4 font-semibold">Top 5 commenters</h3>
        <ul id="topCommenters" class="list-disc pl-6"></ul>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const token = '{{ csrf_token() }}'; // if needed for API
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
                data: { labels, datasets: [{ label: 'Posts by day', data: counts, borderColor: 'blue', fill: false }] },
            });

            document.getElementById('avgComments').textContent = data.avg_comments.toFixed(2);
            const topPosts = document.getElementById('topPosts');
            topPosts.innerHTML = '';
            data.top5_most_commented.forEach(post => {
                topPosts.innerHTML += `<li>${post.title} (${post.comments_count} comments)</li>`;
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
                    datasets: [{ label: 'Comments by hour', data: data.activity_by_hour.map(d => d.total), backgroundColor: 'green' }]
                }
            });

            const ctxWeekday = document.getElementById('commentsByWeekdayChart').getContext('2d');
            new Chart(ctxWeekday, {
                type: 'bar',
                data: {
                    labels: data.activity_by_weekday.map(d => ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][d.weekday-1]),
                    datasets: [{ label: 'Comments by weekday', data: data.activity_by_weekday.map(d => d.total), backgroundColor: 'orange' }]
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
                rolesList.innerHTML += `<li>${role}: ${count}</li>`;
            });

            const topAuthors = document.getElementById('topAuthors');
            topAuthors.innerHTML = '';
            data.top5_authors.forEach(user => {
                topAuthors.innerHTML += `<li>${user.name} (${user.posts_count} posts)</li>`;
            });

            const topCommenters = document.getElementById('topCommenters');
            topCommenters.innerHTML = '';
            data.top5_commenters.forEach(user => {
                topCommenters.innerHTML += `<li>${user.name} (${user.comments_count} comments)</li>`;
            });
        });
});
</script>
@endsection
