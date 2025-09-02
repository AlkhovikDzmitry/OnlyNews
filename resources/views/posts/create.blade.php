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






<!-- с добавлением изображдений нескольких, но надо доработь т.к. при публикации не отображаются все изобрадения, даже вообще не отображаются  -->

<!-- @extends('layouts.app')

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
                            <label class="form-label fw-medium">Изображения</label>
                            
                            <div id="drop-zone" 
                                class="border border-dashed border-2 rounded-3 p-5 text-center"
                                style="border-color: #6c757d !important; cursor: pointer;"
                                ondragover="handleDragOver(event)"
                                ondrop="handleDrop(event)"
                                onclick="document.getElementById('images-input').click()">
                                
                                <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                <p class="mt-2 mb-0">Перетащите изображения сюда или кликните для выбора</p>
                                <small class="text-muted">Максимум 5 изображений</small>
                            </div>
                            
                            <input type="file" id="images-input" name="images[]" 
                                class="d-none" multiple accept="image/*"
                                onchange="handleFileSelect(this)">
                            
                            <div id="images-preview" class="row mt-3"></div>
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


<style>
    .border-dashed {
    border-style: dashed !important;
}

#drop-zone:hover {
    border-color: #0d6efd !important;
    background-color: #f8f9fa;
}
</style>


<script>
function handleDragOver(e) {
    e.preventDefault();
    e.stopPropagation();
    e.currentTarget.style.borderColor = '#0d6efd';
}

function handleDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    e.currentTarget.style.borderColor = '#6c757d';
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        previewMultipleImages(files);
    }
}

function handleFileSelect(input) {
    if (input.files.length > 0) {
        previewMultipleImages(input.files);
    }
}

function previewMultipleImages(files) {
    const previewContainer = document.getElementById('images-preview');
    const existingPreviews = previewContainer.querySelectorAll('.preview-item').length;
    const availableSlots = 5 - existingPreviews;
    
    if (availableSlots <= 0) {
        alert('Максимум 5 изображений');
        return;
    }
    
    const filesToAdd = Array.from(files).slice(0, availableSlots);
    
    filesToAdd.forEach((file, index) => {
        if (file.type.match('image.*')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const totalIndex = existingPreviews + index;
                const col = document.createElement('div');
                col.className = 'col-md-3 col-sm-6 mb-3 preview-item';
                
                col.innerHTML = `
                    <div class="position-relative">
                        <img src="${e.target.result}" 
                             alt="Предпросмотр ${totalIndex + 1}" 
                             class="img-fluid rounded"
                             style="height: 120px; width: 100%; object-fit: cover;">
                        <button type="button" class="btn-close position-absolute top-0 end-0 bg-white"
                                onclick="this.closest('.preview-item').remove()" 
                                style="margin: 5px;"></button>
                    </div>
                `;
                
                previewContainer.appendChild(col);
            };
            
            reader.readAsDataURL(file);
        }
    });
}
</script>
@endsection -->