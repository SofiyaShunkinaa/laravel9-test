@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Заголовок страницы -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-gray-900">
                {{ auth()->user()->isAdmin() ? 'All Comments' : 'My Comments' }}
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                Manage and review your comments across all posts
            </p>
        </div>
        <div class="flex items-center text-sm text-gray-500">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
            </svg>
            {{ $comments->total() }} total comments
        </div>
    </div>

    <!-- Список комментариев -->
    <div class="space-y-6">
        @foreach ($comments as $comment)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 overflow-hidden">
            <!-- Мета-информация -->
            <div class="border-b border-gray-100 px-6 py-4 bg-gray-50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-sm text-gray-600">
                    <div class="flex items-center flex-wrap gap-3 mb-2 sm:mb-0">
                        <!-- Пост -->
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-gray-700 mr-1">Post:</span>
                            <a href="{{ route('posts.show', $comment->post) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200 truncate max-w-xs"
                               title="{{ $comment->post->title }}">
                                {{ $comment->post->title }}
                            </a>
                        </div>

                        <!-- Автор -->
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium text-gray-700 mr-1">Author:</span>
                            <span class="text-gray-900">{{ $comment->author->name }}</span>
                        </div>
                    </div>

                    <!-- Дата -->
                    <div class="flex items-center text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <time datetime="{{ $comment->created_at }}">
                            {{ $comment->created_at->format('M j, Y \\a\\t g:i A') }}
                        </time>
                    </div>
                </div>
            </div>

            <!-- Текст комментария -->
            <div class="px-6 py-4">
                <div class="text-gray-800 leading-relaxed prose prose-sm max-w-none">
                    {{ $comment->body }}
                </div>
            </div>

            <!-- Действия -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                <div class="flex items-center space-x-4">
                    <!-- Редактирование -->
                    @can('update', $comment)
                        <a href="{{ route('dashboard.comments.edit', $comment) }}" 
                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors duration-200"
                           title="Edit comment">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed"
                              title="Not available for your role">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </span>
                    @endcan

                    <!-- Удаление -->
                    @can('delete', $comment)
                        <form action="{{ route('dashboard.comments.destroy', $comment) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition-colors duration-200"
                                    onclick="return confirm('Are you sure you want to delete this comment?')"
                                    title="Delete comment">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </form>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed"
                              title="Not available for your role">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </span>
                    @endcan
                </div>
            </div>
        </div>
        @endforeach

        <!-- Состояние пустого списка -->
        @if($comments->isEmpty())
        <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-100">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No comments found</h3>
            <p class="text-gray-600 max-w-md mx-auto">
                {{ auth()->user()->isAdmin() ? 'There are no comments in the system yet.' : 'You haven\'t written any comments yet.' }}
            </p>
        </div>
        @endif
    </div>

    <!-- Пагинация -->
    @if($comments->hasPages())
    <div class="mt-8">
        {{ $comments->links() }}
    </div>
    @endif
</div>
@endsection