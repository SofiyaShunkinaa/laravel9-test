@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Все посты</h1>

        <!-- Сортировка -->
        <form method="GET" class="mb-6 flex items-center space-x-4">
            <label for="sort_by">Сортировать по:</label>
            <select name="sort_by" id="sort_by" onchange="this.form.submit()"
                class="border-gray-300 rounded">
                <option value="published_at" {{ $sortBy === 'published_at' ? 'selected' : '' }}>Дата публикации</option>
                <option value="title" {{ $sortBy === 'title' ? 'selected' : '' }}>Заголовок</option>
                <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>Дата создания</option>
            </select>

            <select name="direction" onchange="this.form.submit()" class="border-gray-300 rounded">
                <option value="desc" {{ $direction === 'desc' ? 'selected' : '' }}>По убыванию</option>
                <option value="asc" {{ $direction === 'asc' ? 'selected' : '' }}>По возрастанию</option>
            </select>
        </form>

        <!-- Список постов -->
        @foreach ($posts as $post)
            <div class="mb-6 p-4 bg-white shadow rounded">
                <h2 class="text-xl font-semibold">
                    <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                </h2>
                <p class="text-gray-600 text-sm">
                    Автор: {{ $post->author->name }} | Комментариев: {{ $post->comments_count }} | Опубликован: {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d.m.Y') : 'Не опубликован' }}

                </p>
                
            </div>
        @endforeach

        <!-- Пагинация -->
        {{ $posts->links() }}
    </div>
@endsection
