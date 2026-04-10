@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Хлебные крошки --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('posts.category', $post->category->slug ?? '#') }}">
                            {{ $post->category->name ?? 'Без категории' }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 50) }}</li>
                </ol>
            </nav>

            {{-- Заголовок --}}
            <h1 class="display-5 fw-bold mb-3">{{ $post->title }}</h1>

            {{-- Информация о посте --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 text-muted gap-2">
                <div>
                    <i class="bi bi-person-circle me-1"></i>
                    <a href="{{ route('profile', $post->author) }}" class="text-decoration-none">
                        {{ $post->author->name ?? 'Неизвестный автор' }}
                    </a>
                </div>
                <div>
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ $post->created_at->format('d.m.Y') }}
                </div>
                <div>
                    <i class="bi bi-clock me-1"></i>
                    {{ $post->reading_time }} мин чтения
                </div>
                <div>
                    <i class="bi bi-eye me-1"></i>
                    {{ number_format($post->views ?? 0, 0, '', ' ') }} просмотров
                </div>
            </div>

            {{-- Сообщение о статусе (для админа/автора) --}}
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Титульное изображение --}}
            @if($post->image)
                <img src="{{ Storage::url($post->image) }}"
                     alt="{{ $post->title }}"
                     class="img-fluid rounded shadow-sm mb-4 w-100"
                     style="max-height: 500px; object-fit: cover;">
            @else
                <img src="https://via.placeholder.com/800x400?text=Нет+изображения"
                     alt="Нет изображения"
                     class="img-fluid rounded shadow-sm mb-4 w-100"
                     style="max-height: 500px; object-fit: cover;">
            @endif

            {{-- Краткое описание (если есть) --}}
            @if($post->excerpt)
                <div class="lead bg-light p-4 rounded mb-4 fst-italic border-start border-4 border-primary">
                    <i class="bi bi-quote me-2"></i>
                    {{ $post->excerpt }}
                </div>
            @endif

            {{-- Полный текст поста --}}
            <div class="post-content" style="font-size: 1.1rem; line-height: 1.8;">
                {!! $post->content !!}
            </div>

            {{-- Теги --}}
            @if($post->tags && $post->tags->count())
                <div class="mt-5 pt-3">
                    <h6 class="mb-3"><i class="bi bi-tags"></i> Теги:</h6>
                    @foreach($post->tags as $tag)
                        <a href="{{ route('posts.tag', $tag->slug ?? '#') }}" class="badge bg-secondary text-decoration-none me-1 p-2">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Кнопки действий для автора/админа --}}
            @auth
                @if(auth()->id() === $post->user_id || (auth()->user()->is_admin ?? false))
                    <div class="mt-5 d-flex gap-2 justify-content-center">
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="bi bi-pencil me-1"></i> Редактировать
                        </a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                              onsubmit="return confirm('Вы уверены, что хотите удалить этот пост?')"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                                <i class="bi bi-trash me-1"></i> Удалить
                            </button>
                        </form>
                    </div>
                @endif
            @endauth

            {{-- Кнопка "Назад" --}}
            <div class="text-center mt-5 pt-4">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded-pill px-5">
                    <i class="bi bi-arrow-left me-2"></i>Назад
                </a>
            </div>

        </div>
    </div>
</div>

{{-- Стили для контента поста --}}
<style>
    .post-content {
        word-wrap: break-word;
    }

    .post-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 20px 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .post-content figure {
        margin: 20px 0;
    }

    .post-content iframe {
        max-width: 100%;
        border-radius: 8px;
    }

    .post-content blockquote {
        border-left: 4px solid #0d6efd;
        padding-left: 20px;
        margin: 20px 0;
        color: #6c757d;
        font-style: italic;
    }

    .post-content h1,
    .post-content h2,
    .post-content h3,
    .post-content h4,
    .post-content h5,
    .post-content h6 {
        margin-top: 30px;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .post-content p {
        margin-bottom: 20px;
    }

    .post-content ul,
    .post-content ol {
        margin-bottom: 20px;
        padding-left: 20px;
    }

    .post-content li {
        margin-bottom: 8px;
    }

    .post-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .post-content th,
    .post-content td {
        border: 1px solid #dee2e6;
        padding: 8px 12px;
    }

    .post-content th {
        background-color: #f8f9fa;
    }

    .post-content pre {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        overflow-x: auto;
        margin-bottom: 20px;
    }
    
    .post-content code {
        background-color: #f8f9fa;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.9em;
    }
</style>
@endsection
