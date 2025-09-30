@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Основной контент поста -->
    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <!-- Заголовок и мета-информация -->
        <header class="border-b border-gray-100 px-8 py-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-4 leading-tight">{{ $post->title }}</h1>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-sm text-gray-600">
                <div class="flex items-center space-x-4 mb-3 sm:mb-0">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium">{{ $post->author->name }}</span>
                        <span class="text-gray-400 mx-2">•</span>
                        <span>{{ $post->author->email }}</span>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    @if($post->published_at)
                        <time datetime="{{ $post->published_at }}">
                            {{ \Carbon\Carbon::parse($post->published_at)->format('M j, Y \\a\\t g:i A') }}
                        </time>
                    @else
                        <span class="text-orange-500 font-medium">Not published</span>
                    @endif
                </div>
            </div>
        </header>

        <!-- Тело поста -->
        <div class="px-8 py-8">
            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                {!! nl2br(e($post->body)) !!}
            </div>
        </div>
    </article>

    <!-- Секция комментариев -->
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 px-8 py-6">
        <header class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                Comments
                <span class="ml-2 bg-blue-100 text-blue-600 text-sm font-medium px-2 py-1 rounded-full">
                    {{ $post->comments->count() }}
                </span>
            </h2>
        </header>

        <div class="space-y-6">
            @forelse ($post->comments as $comment)
                <div class="border-l-4 border-blue-200 pl-4 py-1">
                    <div class="text-gray-700 leading-relaxed mb-2">
                        {{ $comment->body }}
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium">{{ $comment->author->name ?? 'Unknown' }}</span>
                        <span class="mx-2">•</span>
                        <time datetime="{{ $comment->created_at }}">
                            {{ \Carbon\Carbon::parse($comment->created_at)->format('M j, Y \\a\\t g:i A') }}
                        </time>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                    <p class="text-lg">No comments yet</p>
                    <p class="text-sm mt-1">Be the first to share your thoughts!</p>
                </div>
            @endforelse
        </div>
    </section>

    @auth
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 px-8 py-6 mt-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add a comment
            </h2>

            <form method="POST" action="{{ route('comments.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">

                <div>
                    <textarea 
                        name="body" 
                        rows="4" 
                        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-none" 
                        required 
                        placeholder="Share your thoughts..."
                    ></textarea>
                </div>

                <div class="flex justify-end">
                    <button 
                        type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 font-medium flex items-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                        </svg>
                        Post Comment
                    </button>
                </div>
            </form>
        </section>
    @else
        <div class="bg-blue-50 border border-blue-200 rounded-2xl px-6 py-4 text-center mt-6">
            <p class="text-blue-700">
                Please 
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 underline">log in</a> 
                to leave a comment.
            </p>
        </div>
    @endauth
</div>
@endsection