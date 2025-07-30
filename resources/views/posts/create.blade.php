@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
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
                        
                        <div class="mb-4">
                            <label for="title" class="form-label fw-medium">Заголовок</label>
                            <input type="text" id="title" name="title" 
                                   class="form-control form-control-lg" 
                                   value="{{ old('title') }}" required>
                        </div>
                        
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
                        
                        <div class="mb-4">
                            <label for="content" class="form-label fw-medium">Содержание</label>
                            <textarea id="content" name="content" rows="10"
                                      class="form-control form-control-lg" required>{{ old('content') }}</textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="image" class="form-label fw-medium">Изображение</label>
                            <input type="file" id="image" name="image" 
                                   class="form-control form-control-lg"
                                   onchange="previewImage(this)">
                            <div class="mt-3">
                                <img id="image-preview" src="#" alt="Предпросмотр изображения" 
                                     class="img-fluid rounded d-none"
                                     style="max-height: 300px; object-fit: cover;">
                            </div>
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
</script>
@endsection