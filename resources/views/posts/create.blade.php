@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h1 class="h2 mb-4 text-dark fw-bold">Создать новый пост</h1>

                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Заголовок -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-medium">Заголовок</label>
                            <input type="text" id="title" name="title"
                                   class="form-control form-control-lg"
                                   value="{{ old('title') }}" required>
                        </div>

                        <!-- Категория -->
                        <div class="mb-4">
                            <label for="category_id" class="form-label fw-medium">Категория</label>
                            <select id="category_id" name="category_id" class="form-select form-select-lg" required>
                                <option value="">Выберите категорию</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Титульное изображение -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-medium">Титульное изображение</label>
                            <input type="file" id="image" name="image"
                                   class="form-control form-control-lg"
                                   accept="image/*"
                                   onchange="previewImage(this)">
                            <div class="mt-3">
                                <img id="image-preview" src="#" alt="Предпросмотр"
                                     class="img-fluid rounded d-none"
                                     style="max-height: 300px; object-fit: cover;">
                            </div>
                        </div>

                        <!-- КРАТКОЕ ОПИСАНИЕ (НОВОЕ ПОЛЕ) -->
                        <div class="mb-4">
                            <label for="excerpt" class="form-label fw-medium">Краткое описание</label>
                            <textarea id="excerpt" name="excerpt" rows="3"
                                      class="form-control"
                                      placeholder="Краткое описание поста (будет отображаться в превью)">{{ old('excerpt') }}</textarea>
                            <small class="text-muted">Краткое описание для карточки поста на главной странице</small>
                        </div>

                        <!-- ПОЛНЫЙ ТЕКСТ С РЕДАКТОРОМ -->
                        <div class="mb-4">
                            <label for="content" class="form-label fw-medium">Содержание</label>
                            <textarea id="content" name="content" rows="10"
                                      class="form-control" required>{{ old('content') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('profile') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="bi bi-arrow-left me-2"></i>Назад
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-save me-2"></i>Опубликовать
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Предпросмотр титульного изображения
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.classList.add('d-none');
        }
    }

    // Инициализация Summernote редактора
    $(document).ready(function() {
        $('#content').summernote({
            height: 400,
            placeholder: 'Введите текст поста...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    uploadImage(files[0]);
                }
            }
        });

        // Функция загрузки изображения через редактор
        function uploadImage(file) {
            let formData = new FormData();
            formData.append('image', file);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '{{ route("posts.upload-image") }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.url) {
                        $('#content').summernote('insertImage', response.url);
                    }
                },
                error: function(xhr) {
                    console.log('Ошибка загрузки:', xhr.responseText);
                    alert('Ошибка при загрузке изображения');
                }
            });
        }
    });
</script>

<style>
    .note-editor {
        border-radius: 0.5rem;
        border-color: #dee2e6;
    }
    .note-toolbar {
        border-radius: 0.5rem 0.5rem 0 0;
        background-color: #f8f9fa;
    }
</style>
@endsection
