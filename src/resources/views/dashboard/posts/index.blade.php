@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <div class="flex justify-between mb-4">
        <h1 class="text-2xl font-bold">Мои посты</h1>
        <a href="{{ route('dashboard.posts.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Создать пост</a>
    </div>

    <table class="w-full bg-white shadow rounded">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 text-left">Название</th>
                <th class="px-4 py-2">Комментарии</th>
                <th class="px-4 py-2">Статус</th>
                <th class="px-4 py-2">Опубликовано</th>
                <th class="px-4 py-2">Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr class="border-b">
                <td class="px-4 py-2">{{ $post->title }}</td>
                <td class="px-4 py-2 text-center">{{ $post->comments_count }}</td>
                <td class="px-4 py-2">{{ ucfirst($post->status) }}</td>
                <td class="px-4 py-2">
                    {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d.m.Y H:i') : '—' }}
                </td>
                <td class="px-4 py-2">
    @can('update', $post)
        <a href="{{ route('dashboard.posts.edit', $post) }}" class="text-blue-600 hover:underline">Редактировать</a>
    @else
        <span class="text-gray-400 cursor-not-allowed" title="Недоступно для вашей роли">Редактировать</span>
    @endcan

    @can('delete', $post)
        <form action="{{ route('dashboard.posts.destroy', $post) }}" method="POST" class="inline-block ml-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Удалить пост?')">Удалить</button>
        </form>
    @else
        <button type="button"
                class="text-gray-400 cursor-not-allowed ml-2"
                title="Недоступно для вашей роли"
                disabled>
            Удалить
        </button>
    @endcan
</td>

            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>
@endsection
