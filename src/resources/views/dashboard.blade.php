@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Welcome Message -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            {{ __("You're logged in!") }}
        </div>
    </div>

    <!-- Main Navigation Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Posts -->
        <a href="{{ route('dashboard.posts.index') }}" class="block bg-blue-100 hover:bg-blue-200 transition p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-blue-800 mb-2">Manage Posts</h3>
            <p class="text-sm text-blue-700">View, create, edit and delete blog posts.</p>
        </a>

        <!-- Comments -->
        <a href="{{ route('dashboard.comments.index') }}" class="block bg-green-100 hover:bg-green-200 transition p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-green-800 mb-2">Comments</h3>
            <p class="text-sm text-green-700">Review and moderate comments from users.</p>
        </a>

        @if(auth()->user()->isAdmin())
        <!-- Users -->
        <a href="{{ route('dashboard.users.index') }}" class="block bg-purple-100 hover:bg-purple-200 transition p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-purple-800 mb-2">Users</h3>
            <p class="text-sm text-purple-700">Manage registered users and their roles.</p>
        </a>

        <!-- Statistics -->
        <a href="{{ route('dashboard.stats') }}" class="block bg-yellow-100 hover:bg-yellow-200 transition p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-yellow-800 mb-2">Statistics</h3>
            <p class="text-sm text-yellow-700">View analytics and activity summaries.</p>
        </a>

        <!-- API Endpoints -->
        <div class="block bg-gray-100 p-6 rounded-lg shadow col-span-1 md:col-span-2">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">API Endpoints</h3>
            <ul class="space-y-1 text-sm text-gray-700 list-disc list-inside">
                <li><a href="/api/posts" class="text-blue-600 hover:underline"><code>GET /api/posts</code></a> — List all posts</li>
                <li><a href="/api/comments" class="text-blue-600 hover:underline"><code>GET /api/comments</code></a> — List all comments</li>
                <li><a href="/api/dashboard/stats/posts" class="text-blue-600 hover:underline"><code>GET /api/dashboard/stats/posts</code></a> — Posts statistics</li>
                <li><a href="/api/dashboard/stats/comments" class="text-blue-600 hover:underline"><code>GET /api/dashboard/stats/comments</code></a> — Comments statistics</li>
                <li><a href="/api/dashboard/stats/users" class="text-blue-600 hover:underline"><code>GET /api/dashboard/stats/users</code></a> — Users statistics</li>
                <li><a href="/api/meta/roles" class="text-blue-600 hover:underline"><code>GET /api/meta/roles</code></a> — Roles list</li>
            </ul>
        </div>
        @endif

    </div>

</div>
@endsection
