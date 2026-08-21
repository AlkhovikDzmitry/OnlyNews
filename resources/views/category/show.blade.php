@extends('layouts.app')

@section('content')
<div class="mb-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <h1 class="mb-3">{{ $category->name }}</h1>

    @if($category->description)
        <p class="text-muted mb-4">{{ $category->description }}</p>
    @endif

    @if($posts->isEmpty())
        <div class="alert alert-info">В этой категории пока нет публикаций.</div>
    @else
        <div class="row g-4">
            @foreach($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ $post->image_url }}"
                             class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">

                        <div class="card-body">
                            <small class="text-muted d-block mb-2">Дата публикации: {{ $post->created_at->format('d.m.Y') }}</small>

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
                                         class="rounded-circle me-2" width="32" height="32" alt="{{ $post->author->name }}">
                                    <small>{{ $post->author->name }}</small>
                                </div>
                                <small class="text-muted">{{ $post->reading_time }} мин</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
