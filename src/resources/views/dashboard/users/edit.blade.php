@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Заголовок страницы -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Change User Role</h1>
        <p class="text-gray-600">Update permissions and access level for this user</p>
    </div>

    <!-- Информация о пользователе -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 font-medium text-lg">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </span>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-lg font-semibold text-gray-900 truncate">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                <div class="flex items-center mt-1">
                    @php
                        $roleColors = [
                            'admin' => 'bg-purple-100 text-purple-800',
                            'editor' => 'bg-blue-100 text-blue-800',
                            'author' => 'bg-green-100 text-green-800',
                            'user' => 'bg-gray-100 text-gray-800'
                        ];
                        $currentColor = $roleColors[$user->role->name] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $currentColor }}">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Current: {{ ucfirst($user->role->name) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Форма изменения роли -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('dashboard.users.update', $user) }}">
            @csrf
            @method('PUT')

            <!-- Выбор роли -->
            <div class="mb-6">
                <label for="role_id" class="block text-sm font-medium text-gray-700 mb-3">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Select New Role
                    </div>
                </label>
                
                <div class="space-y-3">
                    @foreach ($roles as $role)
                    @php
                        $roleColor = [
                            'admin' => 'border-purple-200 bg-purple-50 text-purple-700',
                            'editor' => 'border-blue-200 bg-blue-50 text-blue-700',
                            'author' => 'border-green-200 bg-green-50 text-green-700',
                            'user' => 'border-gray-200 bg-gray-50 text-gray-700'
                        ][$role->name] ?? 'border-gray-200 bg-gray-50 text-gray-700';
                        
                        $selectedColor = $user->role_id == $role->id ? 'ring-2 ring-blue-500 ring-offset-2' : '';
                    @endphp
                    
                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 hover:shadow-md {{ $roleColor }} {{ $selectedColor }}">
                        <input 
                            type="radio" 
                            name="role_id" 
                            value="{{ $role->id }}" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                            {{ $user->role_id == $role->id ? 'checked' : '' }}
                        >
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="block text-sm font-medium">{{ ucfirst($role->name) }}</span>
                                @if($user->role_id == $role->id)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Current
                                </span>
                                @endif
                            </div>
                            <p class="text-sm opacity-75 mt-1">
                                @if($role->name === 'admin')
                                    Full system access and management capabilities
                                @elseif($role->name === 'editor')
                                    Can edit and manage content and comments
                                @else
                                    Can only view content, can't comment on posts
                                @endif
                            </p>
                        </div>
                    </label>
                    @endforeach
                </div>
                
                @error('role_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Кнопки действий -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('dashboard.users.index') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 mb-3 sm:mb-0 justify-center sm:justify-start">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Users
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Role
                </button>
            </div>
        </form>
    </div>
</div>
@endsection