@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">
        {{ auth()->user()->isAdmin() ? 'Все комментарии' : 'Мои комментарии' }}
    </h1>

    @foreach ($comments as $comment)
        <div class="bg-white shadow p-4 mb-4 rounded">
            <div class="text-sm text-gray-600 mb-1">
                К посту: 
                <a href="{{ route('posts.show', $comment->post) }}" class="text-blue-600 hover:underline">
                    {{ $comment->post->title }}
                </a>
                | Автор: {{ $comment->author->name }}
                <span class="text-gray-400">({{ $comment->created_at->format('d.m.Y H:i') }})</span>
            </div>

            <div class="text-gray-800 mb-2">
                {{ $comment->body }}
            </div>

            <div class="flex gap-3 text-sm">
            @can('update', $comment)
                <a href="{{ route('dashboard.comments.edit', $comment) }}" class="text-blue-600 hover:underline">
                    Редактировать
                </a>
            @else
                <span class="text-gray-400 cursor-not-allowed" title="Недоступно для вашей роли">
                    Редактировать
                </span>
            @endcan

            @can('delete', $comment)
                <form action="{{ route('dashboard.comments.destroy', $comment) }}" method="POST"
                    onsubmit="return confirm('Удалить комментарий?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:underline">Удалить</button>
                </form>
            @else
                <button type="button" disabled
                        class="text-gray-400 cursor-not-allowed"
                        title="Недоступно для вашей роли">
                    Удалить
                </button>
            @endcan
        </div>
                </div>
    @endforeach

    {{ $comments->links() }}
</div>
@endsection
