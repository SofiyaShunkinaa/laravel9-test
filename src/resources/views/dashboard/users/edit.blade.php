@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Изменить роль пользователя</h1>

    <form method="POST" action="{{ route('dashboard.users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="role_id" class="block mb-1">Роль</label>
            <select name="role_id" id="role_id" class="w-full border rounded px-3 py-2">
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected($user->role_id == $role->id)>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Сохранить
            </button>

            <a href="{{ route('dashboard.users.index') }}" class="text-gray-600 hover:underline">Назад</a>
        </div>
    </form>
</div>
@endsection
