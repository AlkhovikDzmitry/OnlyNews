@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <h1 class="h2 mb-5 text-dark fw-bold">Личный кабинет</h1>
                    
                    <div class="row">
                        <!-- Боковая панель -->
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="text-center">
                                <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=random' }}" 
                                     id="avatar-preview"
                                     class="rounded-circle object-cover border border-3 border-primary mb-4"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                                
                                <form id="avatar-form" method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="avatar" id="avatar-input" class="d-none" accept="image/*">
                                    <label for="avatar-input" 
                                           class="btn btn-outline-primary px-4 py-2 rounded-pill cursor-pointer">
                                        <i class="bi bi-camera me-2"></i>Сменить аватар
                                    </label>
                                </form>
                                
                                <h2 class="h4 mt-4 mb-1 fw-bold text-dark">{{ Auth::user()->name }}</h2>
                                <p class="text-muted small">{{ Auth::user()->email }}</p>
                                
                                <div class="mt-4 pt-3 border-top">
                                    <a href="{{ route('logout') }}" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                       class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="bi bi-box-arrow-right me-1"></i>Выйти
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Основная информация -->
                        <div class="col-md-8">
                            <div class="mb-5">
                                <h2 class="h4 mb-4 fw-bold text-dark">Мои данные</h2>
                                <form method="POST" action="{{ route('profile.update') }}">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label fw-medium">Имя</label>
                                            <input type="text" id="name" name="name" value="{{ Auth::user()->name }}"
                                                   class="form-control form-control-lg">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label fw-medium">Email</label>
                                            <input type="email" id="email" name="email" value="{{ Auth::user()->email }}"
                                                   class="form-control form-control-lg">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="bio" class="form-label fw-medium">О себе</label>
                                        <textarea id="bio" name="bio" rows="3"
                                                  class="form-control form-control-lg">{{ Auth::user()->bio }}</textarea>
                                    </div>
                                    
                                    <button type="submit" 
                                            class="btn btn-primary px-4 py-2 rounded-pill">
                                        <i class="bi bi-save me-2"></i>Сохранить изменения
                                    </button>
                                </form>
                            </div>
                            
                            <div class="pt-4 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h2 class="h4 fw-bold text-dark">Мои посты</h2>
                                    @if(Auth::user()->posts->isNotEmpty())
                                        <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-pill">
                                            <i class="bi bi-plus-circle me-1"></i>Новый пост
                                        </a>
                                    @endif
                                </div>
                                <div class="list-group list-group-flush">
                                    @forelse(Auth::user()->posts as $post)
                                        <div class="list-group-item border-0 py-3 px-0">
                                            <div class="row g-3">
                                                <!-- Изображение поста -->
                                                <div class="col-md-3">
                                                    <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none">
                                                        <div class="ratio ratio-1x1 rounded overflow-hidden bg-light">
                                                            @if($post->image)
                                                                <img src="{{ asset('storage/' . $post->image) }}" 
                                                                     alt="{{ $post->title }}"
                                                                     class="img-fluid object-fit-cover">
                                                            @else
                                                                <div class="d-flex align-items-center justify-content-center bg-secondary text-white">
                                                                    <i class="bi bi-image fs-1"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </a>
                                                </div>
                                                
                                                <!-- Контент поста -->
                                                <div class="col-md-9">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none flex-grow-1">
                                                            <h3 class="h5 mb-1 text-dark">{{ $post->title }}</h3>
                                                        </a>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-secondary rounded-circle" type="button" 
                                                                    id="dropdownMenuButton-{{ $post->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="bi bi-three-dots-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton-{{ $post->id }}">
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('posts.edit', $post->id) }}">
                                                                        <i class="bi bi-pencil me-2"></i>Редактировать
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deletePostModal" data-post-id="{{ $post->id }}">
                                                                        <i class="bi bi-trash-fill me-2"></i>Удалить пост
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <p class="mb-2 text-muted small">{{ Str::limit($post->excerpt, 100) }}</p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="badge bg-light text-dark rounded-pill">
                                                            {{ $post->created_at->format('d.m.Y') }}
                                                        </span>
                                                        <div>
                                                            @if($post->published_at > now())
                                                                <span class="badge bg-warning text-dark">Ожидает публикации</span>
                                                            @endif
                                                            <span class="badge bg-light text-dark ms-2">
                                                                <i class="bi bi-eye"></i> {{ $post->views }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4">
                                            <i class="bi bi-journal-text fs-1 text-muted mb-3"></i>
                                            <p class="text-muted mb-4">У вас пока нет постов</p>
                                            <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-pill px-4">
                                                <i class="bi bi-plus-circle me-1"></i>Создать первый пост
                                            </a>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно подтверждения удаления -->
<div class="modal fade" id="deletePostModal" tabindex="-1" aria-labelledby="deletePostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="deletePostModalLabel">Удалить пост</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                Вы действительно хотите удалить этот пост? Это действие необратимо.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                <form id="deletePostForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('deletePostModal').addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var postId = button.getAttribute('data-post-id');
    var form = document.getElementById('deletePostForm');
    form.action = '/posts/' + postId;
});

// Обработка загрузки аватара
document.getElementById('avatar-input').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
        document.getElementById('avatar-form').submit();
    }
});
</script>
@endsection