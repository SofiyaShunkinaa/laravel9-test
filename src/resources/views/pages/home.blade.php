@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Форма поиска -->
    <form method="GET" action="{{ route('home') }}" class="mb-6 flex flex-wrap gap-2 items-end">
        <div>
            <label for="q" class="block text-gray-700 mb-1">Поиск</label>
            <input type="text" name="q" id="q" value="{{ old('q', $q) }}" class="border rounded px-2 py-1">
        </div>

        <div>
            <label for="status" class="block text-gray-700 mb-1">Статус</label>
            <select name="status" id="status" class="border rounded px-2 py-1">
                <option value="">Все</option>
                <option value="draft" @selected($status === 'draft')>Черновик</option>
                <option value="published" @selected($status === 'published')>Опубликованные</option>
            </select>
        </div>

        <div>
            <label for="from" class="block text-gray-700 mb-1">Дата от</label>
            <input type="date" name="from" id="from" value="{{ $from }}" class="border rounded px-2 py-1">
        </div>

        <div>
            <label for="to" class="block text-gray-700 mb-1">Дата до</label>
            <input type="date" name="to" id="to" value="{{ $to }}" class="border rounded px-2 py-1">
        </div>

        <div>
            <label class="block mb-1">&nbsp;</label>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Применить</button>
        </div>

        <div>
            <label class="block mb-1">&nbsp;</label>
            <a href="{{ route('home') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                Сбросить параметры поиска
            </a>
        </div>
    </form>

    <h1 class="text-2xl font-bold mb-4">Все посты</h1>

    <!-- Форма сортировки -->
    <form method="GET" class="mb-6 flex items-center space-x-4">
        <label for="sort_by">Сортировать по:</label>
        <select name="sort_by" id="sort_by" onchange="this.form.submit()" class="border-gray-300 rounded">
            <option value="published_at" {{ $sortBy === 'published_at' ? 'selected' : '' }}>Дата публикации</option>
            <option value="title" {{ $sortBy === 'title' ? 'selected' : '' }}>Заголовок</option>
            <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>Дата создания</option>
        </select>

        <select name="direction" onchange="this.form.submit()" class="border-gray-300 rounded">
            <option value="desc" {{ $direction === 'desc' ? 'selected' : '' }}>По убыванию</option>
            <option value="asc" {{ $direction === 'asc' ? 'selected' : '' }}>По возрастанию</option>
        </select>

        <!-- Сохраняем текущий поиск и фильтры при смене сортировки -->
        <input type="hidden" name="q" value="{{ $q }}">
        <input type="hidden" name="status" value="{{ $status }}">
        <input type="hidden" name="from" value="{{ $from }}">
        <input type="hidden" name="to" value="{{ $to }}">
    </form>

    <!-- Список постов -->
    @foreach ($posts as $post)
        <div class="mb-6 p-4 bg-white shadow rounded">
            <h2 class="text-xl font-semibold">
                <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
            </h2>
            <p class="text-gray-600 text-sm">
                Автор: {{ $post->author->name }} | 
                Комментариев: {{ $post->comments_count }} | 
                Опубликован: {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d.m.Y') : 'Не опубликован' }}
            </p>
        </div>
    @endforeach

    <!-- Пагинация -->
    {{ $posts->withQueryString()->links() }}
</div>
@endsection
