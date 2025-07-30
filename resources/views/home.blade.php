@extends('layouts.app')

@section('content')
<div class="mb-5">
    <h1 class="mb-4">Все публикации</h1>
    
    <!-- Фильтры -->
    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
        <span class="me-2">Категории:</span>
        @foreach($categories as $category)
            <a href="{{ route('category.show', $category->slug) }}" 
               class="btn btn-sm btn-outline-secondary rounded-pill">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    <!-- Посты -->
    <div class="row g-4">
        @foreach($posts as $post)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ $post->image_url ?? 'https://via.placeholder.com/600x400' }}" 
                     class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Дата публикации: {{ $post->created_at->format('d.m.Y') }}</small>

                        <span class="badge bg-primary">
                            {{ $post->category->name }}
                        </span>
                        <span class="badge bg-secondary" title="Количество комментариев">
                                <i class="bi bi-chat-left-text"></i> {{ $post->comments_count }}
                            </span>
                        </div>
                   
                    
                    <h2 class="h5 card-title">
                        <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none">
                            {{ $post->title }}
                        </a>
                    </h2>
                    
                    <p class="card-text text-muted">{{ Str::limit($post->excerpt, 100) }}</p>
                </div>
                
                <div class="card-footer bg-transparent border-top-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="{{ $post->author->avatar_url }}" 
                                 class="rounded-circle me-2" width="32" height="32">
                            <small>{{ $post->author->name }}</small>
                        </div>
                        <small class="text-muted">{{ $post->reading_time }} мин</small>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Пагинация -->
    <div class="mt-5">
        {{ $posts->links() }}
    </div>
</div>
@endsection