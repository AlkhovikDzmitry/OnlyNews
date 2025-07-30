@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <article class="card shadow-sm mb-5">
                <img src="{{ $post->image_url }}" class="card-img-top" alt="{{ $post->title }}">
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="badge bg-primary rounded-pill">{{ $post->category->name }}</span>
                        <span class="text-muted">{{ $post->reading_time_text }}</span>
                    </div>
                    
                    <h1 class="h2 mb-3">{{ $post->title }}</h1>
                    
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ $post->author->avatar_url }}" 
                             class="rounded-circle me-2" 
                             width="40" height="40" 
                             alt="{{ $post->author->name }}">
                        <div>
                            <div class="fw-medium">{{ $post->author->name }}</div>
                            <div class="text-muted small">
                                {{ $post->published_at->format('d.m.Y H:i') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="post-content mb-4">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                    
                    @if($post->tags->isNotEmpty())
                        <div class="mt-4 pt-3 border-top">
                            @foreach($post->tags as $tag)
                                <span class="badge bg-secondary me-1 mb-1">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </article>
            
            <div class="text-center">
                <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i>Вернуться на главную
                </a>
            </div>
        </div>
    </div>
</div>


<section class="mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm"> 
                <div class="card-body p-5"> 
                    <h3 class="h4 mb-4 comment-count">Комментарии ({{ $post->comments->count() }})</h3>

                    @auth
                    <form id="comment-form" method="POST" action="{{ route('comments.store', $post) }}" class="mb-4">
                        @csrf
                        <div class="form-floating mb-3">
                            <textarea class="form-control" name="content" 
                                    placeholder="Оставьте комментарий" 
                                    required
                                    style="height: 120px; min-height: 120px; resize: none;"></textarea>
                            <label>Ваш комментарий</label>
                        </div>
                        <div class="text-end"> 
                            <button type="submit" class="btn btn-primary px-4">
                                <span class="submit-text">Отправить</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-info">
                        <a href="{{ route('login') }}" class="alert-link">Войдите</a>, чтобы оставить комментарий
                    </div>
                    @endauth

                    <div class="comments mt-4">
                        @foreach($post->comments as $comment)
                        <div class="comment mb-4 pb-3 border-bottom">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $comment->user->avatar_url ?? asset('images/default-avatar.png') }}" 
                                     class="rounded-circle me-2" width="40" height="40">
                                <div>
                                    <strong>{{ $comment->user->name }}</strong>
                                    <div class="text-muted small">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            <div class="comment-content">
                                {{ $comment->content }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<script>
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.querySelector('#comment-form');
    
    if (commentForm) {
        const textarea = commentForm.querySelector('textarea');
        
        // Обработка нажатия Enter (без Shift)
        textarea.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                commentForm.dispatchEvent(new Event('submit'));
            }
        });
        
        commentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const submitText = submitButton.querySelector('.submit-text');
            const spinner = submitButton.querySelector('.spinner-border');
            
            // Показываем спиннер
            submitText.classList.add('d-none');
            spinner.classList.remove('d-none');
            submitButton.disabled = true;
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) throw new Error('Network response was not ok');
                
                const data = await response.json();
                
                if (data.success) {
                    // Добавляем новый комментарий В НАЧАЛО списка
                    const commentsList = document.querySelector('.comments');
                    const newComment = createCommentElement(data.comment);
                    
                    // Если есть другие комментарии, вставляем перед первым
                    if (commentsList.firstChild) {
                        commentsList.insertBefore(newComment, commentsList.firstChild);
                    } else {
                        // Если нет комментариев, просто добавляем
                        commentsList.appendChild(newComment);
                    }
                    
                    // Очищаем форму
                    this.reset();
                    
                    // Обновляем счетчик
                    updateCommentCount(data.comments_count);
                    
                    // Плавная прокрутка к новому комментарию
                    setTimeout(() => {
                        newComment.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'nearest'
                        });
                    }, 50);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Произошла ошибка при отправке комментария');
            } finally {
                // Восстанавливаем кнопку
                submitText.classList.remove('d-none');
                spinner.classList.add('d-none');
                submitButton.disabled = false;
            }
        });
    }
    
    function createCommentElement(comment) {
        const div = document.createElement('div');
        div.className = 'comment mb-4 pb-3 border-bottom';
        div.style.opacity = '0';
        div.style.transform = 'translateY(-20px)';
        div.style.transition = 'all 0.3s ease-out';
        
        div.innerHTML = `
            <div class="d-flex align-items-center mb-2">
                <img src="${comment.user.avatar_url || '{{ asset('images/default-avatar.png') }}'}" 
                     class="rounded-circle me-2" width="40" height="40" alt="${comment.user.name}">
                <div>
                    <strong>${comment.user.name}</strong>
                    <div class="text-muted small">
                        Только что
                    </div>
                </div>
            </div>
            <div class="comment-content mt-2">
                ${comment.content.replace(/\n/g, '<br>')}
            </div>
        `;
        
        // Анимация появления
        setTimeout(() => {
            div.style.opacity = '1';
            div.style.transform = 'translateY(0)';
        }, 10);
        
        return div;
    }
    
    function updateCommentCount(count) {
        const counter = document.querySelector('.comment-count');
        if (counter) {
            counter.textContent = `Комментарии (${count})`;
        }
    }
});
</script>

@endsection