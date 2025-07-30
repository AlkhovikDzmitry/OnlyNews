@extends('layouts.app')

@section('content')

<div class="mb-5">
    <h1 class="mb-4">Категория: {{ $category->name }}</h1>
  

    @foreach($posts as $post)
        <div class="mb-4 pb-3 border-bottom d-flex flex-column" style="max-width: 600px;">
            <small class="text-muted d-block mb-1">Дата публикации: {{ $post->created_at->format('d.m.Y') }}</small>

            <img src="{{ $post->image_url ?? 'https://via.placeholder.com/600x400' }}" 
                 alt="{{ $post->title }}"
                 style="width: 100%; height: 400px; object-fit: cover;"
                 class="rounded shadow-sm mb-2">
                 
            <div style="width: 100%;">
                <h2 class="h5 mb-2">
                    <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none">
                        {{ $post->title }}
                    </a>
                </h2>

                <p class="text-muted mb-2" style="width: 100%;">{{ Str::limit($post->excerpt, 140) }}</p>

                <small class="text-muted">
                    Автор: {{ $post->author->name }} · 
                    {{ $post->reading_time }} мин чтения
                </small>
            </div>
        </div>
        
    @endforeach

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>

   <div class="text-center">
                <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i>Вернуться на главную
                </a>
            </div>
@endsection