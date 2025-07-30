@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h4 mb-0 text-dark fw-bold">Редактировать пост</h1>
                        <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i> Просмотр
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Заголовок -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-medium">Заголовок *</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}"
                                   class="form-control form-control-lg @error('title') is-invalid @enderror" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Содержание -->
                        <div class="mb-4">
                            <label for="content" class="form-label fw-medium">Содержание *</label>
                            <textarea id="content" name="content" rows="8"
                                      class="form-control form-control-lg @error('content') is-invalid @enderror" required>{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Категория -->
                        <div class="mb-4">
                            <label for="category_id" class="form-label fw-medium">Категория *</label>
                            <select id="category_id" name="category_id" 
                                    class="form-select form-select-lg @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Выберите категорию --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Теги -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Теги</label>
                            <div class="row g-2">
                                @foreach($tags as $tag)
                                    <div class="col-md-3 col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="tags[]" value="{{ $tag->id }}" 
                                                   id="tag-{{ $tag->id }}"
                                                   {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="tag-{{ $tag->id }}">
                                                {{ $tag->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('tags')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Изображение -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-medium">Изображение</label>
                            
                            @if($post->image)
                                <div class="mb-3">
                                    <img src="{{ Storage::url($post->image) }}" alt="Текущее изображение" 
                                        class="img-thumbnail mb-2" style="max-height: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                            name="remove_image" id="remove_image" value="1">
                                        <label class="form-check-label text-danger" for="remove_image">
                                            <i class="bi bi-trash me-1"></i> Удалить текущее изображение
                                        </label>
                                    </div>
                                </div>
                            @endif
                            
                            <input type="file" id="image" name="image" 
                                class="form-control form-control-lg @error('image') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/gif,image/webp">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Дата публикации -->
                        <div class="mb-4">
                            <label for="published_at" class="form-label fw-medium">Дата публикации</label>
                            <input type="datetime-local" id="published_at" name="published_at" 
                                   value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}"
                                   class="form-control form-control-lg @error('published_at') is-invalid @enderror">
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Кнопки -->
                        <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                            <a href="{{ route('profile') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-left me-1"></i> Отмена
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-save me-1"></i> Обновить пост
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .img-thumbnail {
        max-width: 100%;
        height: auto;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const removeCheckbox = document.getElementById('remove_image');
    
    if (removeCheckbox) {
        removeCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // Делаем file input необязательным при удалении изображения
                imageInput.removeAttribute('required');
            } else {
                // Если checkbox снят, делаем file input обязательным
                if (!imageInput.value) {
                    imageInput.setAttribute('required', 'required');
                }
            }
        });
    }
});
</script>
@endpush