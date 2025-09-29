@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Edit post</h1>

    <form method="POST" action="{{ route('dashboard.posts.update', $post) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block text-gray-700 font-medium mb-1">Title</label>
            <input type="text" name="title" id="title" class="w-full border-gray-300 rounded px-3 py-2"
                value="{{ old('title', $post->title) }}" required>
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="body" class="block text-gray-700 font-medium mb-1">Post text</label>
            <textarea name="body" id="body" rows="8" class="w-full border-gray-300 rounded px-3 py-2" required>{{ old('body', $post->body) }}</textarea>
            @error('body')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <input type="hidden" name="status" id="status" value="{{ $post->status }}">

        <div class="flex items-center justify-between mt-6">
            <div>
                <button type="submit" name="action" value="draft"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Save changes
                </button>

                <button type="submit" name="action" value="publish"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 ml-2">
                    Publish
                </button>
            </div>

            <a href="{{ url()->previous() }}" class="text-gray-600 hover:underline">Back</a>
        </div>
    </form>
</div>
@endsection
