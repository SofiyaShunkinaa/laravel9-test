@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Заголовок страницы -->
    <header class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Discover Posts</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Find interesting content across Laragram. Search by keywords, filter by date, and sort to find exactly what you're looking for</p>
    </header>

    <!-- Поисковая панель -->
    <section class="mb-12">
        <div class="bg-white rounded-2xl shadow-lg p-8 max-w-4xl mx-auto">
            <form id="searchForm" class="space-y-6">
                <!-- Основная строка поиска -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                name="q" 
                                id="q" 
                                placeholder="Search posts by title or content..." 
                                class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                            >
                        </div>
                    </div>
                    <button 
                        type="submit" 
                        class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 font-medium flex items-center justify-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </button>
                </div>

                <!-- Фильтры по дате -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-100">
                    <div>
                        <label for="from" class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input 
                                type="date" 
                                name="from" 
                                id="from" 
                                class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="to" class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input 
                                type="date" 
                                name="to" 
                                id="to" 
                                class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                            >
                        </div>
                    </div>

                    <div class="flex items-end space-x-3">
                        <button 
                            type="button" 
                            id="resetFilters" 
                            class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-200 font-medium flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Сортировка и результаты -->
    <section class="max-w-4xl mx-auto">
        <!-- Панель сортировки -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 sm:mb-0">All Posts</h2>
            
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-700">Sort by:</span>
                <div class="flex space-x-3">
                    <select name="sort_by" id="sort_by" class="border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-2 pl-3 pr-10 text-sm transition-colors duration-200">
                        <option value="created_at">Created date</option>
                        <option value="title">Title</option>
                        <option value="comments_count">Comments count</option>
                    </select>

                    <select name="sort_dir" id="sort_dir" class="border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-2 pl-3 pr-10 text-sm transition-colors duration-200">
                        <option value="desc">Descending</option>
                        <option value="asc">Ascending</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Индикатор загрузки -->
        <div id="loading" class="text-center py-12 hidden">
            <div class="inline-flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
                <p class="text-gray-600 font-medium">Loading posts...</p>
            </div>
        </div>

        <!-- Контейнер постов -->
        <div id="postsContainer" class="space-y-6">
            <!-- Posts will be loaded here -->
        </div>

        <!-- Пагинация -->
        <div id="paginationContainer" class="mt-12">
            <!-- Pagination will be loaded here -->
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let currentParams = {};

    // Load posts function
    function loadPosts(page = 1) {
        const loading = document.getElementById('loading');
        const postsContainer = document.getElementById('postsContainer');
        const paginationContainer = document.getElementById('paginationContainer');
        
        loading.classList.remove('hidden');
        postsContainer.innerHTML = '';
        paginationContainer.innerHTML = '';

        const params = new URLSearchParams({
            page: page,
            per_page: 10,
            sort_by: document.getElementById('sort_by').value,
            sort_dir: document.getElementById('sort_dir').value,
            ...currentParams
        });

        fetch(`/api/posts?${params}`)
            .then(response => response.json())
            .then(data => {
                loading.classList.add('hidden');
                renderPosts(data.data);
                renderPagination(data);
            })
            .catch(error => {
                loading.classList.add('hidden');
                postsContainer.innerHTML = `
                    <div class="text-center py-12 bg-red-50 rounded-lg">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100 text-red-600 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-red-600 font-medium">Error loading posts. Please try again.</p>
                    </div>
                `;
                console.error('Error:', error);
            });
    }

    // Render posts
    function renderPosts(posts) {
        const container = document.getElementById('postsContainer');
        
        if (posts.length === 0) {
            container.innerHTML = `
                <div class="text-center py-16 bg-gray-50 rounded-2xl">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No posts found</h3>
                    <p class="text-gray-600 max-w-md mx-auto">Try adjusting your search criteria or reset the filters to see more results.</p>
                </div>
            `;
            return;
        }

        const postsHtml = posts.map(post => `
            <article class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 p-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-3">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2 sm:mb-0">
                        <a href="/posts/${post.id}" class="hover:text-blue-600 transition-colors duration-200">${post.title}</a>
                    </h2>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                            ${post.comments_count} comments
                        </span>
                    </div>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <span class="flex items-center mr-4">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        ${post.author.name}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        ${new Date(post.created_at).toLocaleDateString()}
                    </span>
                </div>
            </article>
        `).join('');

        container.innerHTML = postsHtml;
    }

    // Render pagination
    function renderPagination(data) {
        const container = document.getElementById('paginationContainer');
        
        if (data.last_page <= 1) return;

        let paginationHtml = '<div class="flex justify-center space-x-2">';
        
        // Previous button
        if (data.current_page > 1) {
            paginationHtml += `
                <button onclick="loadPosts(${data.current_page - 1})" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Previous
                </button>
            `;
        }

        // Page numbers
        const startPage = Math.max(1, data.current_page - 2);
        const endPage = Math.min(data.last_page, data.current_page + 2);

        for (let i = startPage; i <= endPage; i++) {
            const activeClass = i === data.current_page 
                ? 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700' 
                : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50';
            paginationHtml += `
                <button onclick="loadPosts(${i})" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 ${activeClass}">
                    ${i}
                </button>
            `;
        }

        // Next button
        if (data.current_page < data.last_page) {
            paginationHtml += `
                <button onclick="loadPosts(${data.current_page + 1})" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center">
                    Next
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            `;
        }

        paginationHtml += '</div>';
        container.innerHTML = paginationHtml;
    }

    // Search form handler
    document.getElementById('searchForm').addEventListener('submit', function(e) {
        e.preventDefault();
        currentParams = {
            q: document.getElementById('q').value,
            from: document.getElementById('from').value,
            to: document.getElementById('to').value
        };
        currentPage = 1;
        loadPosts();
    });

    // Reset filters handler
    document.getElementById('resetFilters').addEventListener('click', function() {
        document.getElementById('q').value = '';
        document.getElementById('from').value = '';
        document.getElementById('to').value = '';
        currentParams = {};
        currentPage = 1;
        loadPosts();
    });

    // Sort handlers
    document.getElementById('sort_by').addEventListener('change', function() {
        currentPage = 1;
        loadPosts();
    });

    document.getElementById('sort_dir').addEventListener('change', function() {
        currentPage = 1;
        loadPosts();
    });

    // Make loadPosts globally available for pagination buttons
    window.loadPosts = loadPosts;

    // Load initial posts
    loadPosts();
});
</script>
@endsection