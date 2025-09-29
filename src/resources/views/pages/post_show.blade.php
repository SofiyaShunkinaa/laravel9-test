@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8">

        <!-- Пост -->
        <div class="bg-white shadow-md rounded px-6 py-4 mb-8">
            <h1 class="text-3xl font-bold mb-2">{{ $post->title }}</h1>

            <div class="text-sm text-gray-600 mb-4">
                Автор: {{ $post->author->name }} ({{ $post->author->email }})<br>
                Опубликовано: 
                {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d.m.Y H:i') : 'не опубликован' }}
            </div>

            <div class="text-gray-800 leading-relaxed">
                {!! nl2br(e($post->body)) !!}
            </div>
        </div>

        <!-- Комментарии -->
        <div class="bg-white shadow-md rounded px-6 py-4">
            <h2 class="text-xl font-semibold mb-4">Комментарии ({{ $post->comments->count() }})</h2>

            @forelse ($post->comments as $comment)
                <div class="border-b border-gray-200 pb-3 mb-3">
                    <div class="text-gray-700">
                        {{ $comment->body }}
                    </div>
                    <div class="text-sm text-gray-500 mt-1">
                        Автор: {{ $comment->author->name ?? 'Неизвестно' }} |
                        {{ \Carbon\Carbon::parse($comment->created_at)->format('d.m.Y H:i') }}
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Пока нет комментариев.</p>
            @endforelse
        </div>

        <!-- Форма добавления комментария -->
        @auth
            <div class="bg-white shadow-md rounded px-6 py-4 mt-6">
                <h2 class="text-lg font-semibold mb-2">Добавить комментарий</h2>

                <form method="POST" action="{{ route('comments.store') }}">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">

                    <div class="mb-4">
                        <textarea name="body" rows="4" class="w-full border-gray-300 rounded" required placeholder="Ваш комментарий..."></textarea>
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Отправить
                    </button>
                </form>
            </div>
        @endauth

    </div>
@endsection
