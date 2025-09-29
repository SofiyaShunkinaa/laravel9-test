@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Редактировать комментарий</h1>

    <form method="POST" action="{{ route('dashboard.comments.update', $comment) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="body" class="block text-gray-700 font-medium mb-1">Текст комментария</label>
            <textarea name="body" id="body" rows="6" class="w-full border-gray-300 rounded px-3 py-2" required>{{ old('body', $comment->body) }}</textarea>
            @error('body')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mt-6">
            <div>
                <button type="submit"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Сохранить изменения
                </button>
            </div>

            <a href="{{ url()->previous() }}" class="text-gray-600 hover:underline">Назад</a>
        </div>
    </form>
</div>
@endsection
