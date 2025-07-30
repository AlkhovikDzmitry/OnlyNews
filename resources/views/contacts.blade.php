@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow-sm mb-4">
            <div class="card-body p-5">
                <h1 class="card-title mb-4">Свяжитесь с нами</h1>
                <p class="text-muted mb-4">Есть вопросы или предложения по размещению рекламы? Заполните форму, и мы ответим в течение 24 часов.</p>
                
                <div class="mb-4">
                    <div class="d-flex mb-3">
                        <i class="bi bi-envelope fs-4 text-primary me-3"></i>
                        <div>
                            <h3 class="h6 mb-0">Email</h3>
                            <p class="text-muted mb-0">alkhovik86@gmail.com</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <i class="bi bi-telephone fs-4 text-primary me-3"></i>
                        <div>
                            <h3 class="h6 mb-0">Телефон</h3>
                            <p class="text-muted mb-0">+37 5(29) 777-66-55</p>
                        </div>
                    </div>
                    
                    <div class="d-flex">
                        <i class="bi bi-geo-alt fs-4 text-primary me-3"></i>
                        <div>
                            <h3 class="h6 mb-0">Адрес</h3>
                            <p class="text-muted mb-0">г. Минск, ул. Карского, д. 23</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body p-5">
                <form method="POST" action="{{ route('feedback.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Ваше имя</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    
                    <div class="mb-4">
                        <label for="message" class="form-label">Сообщение</label>
                        <textarea class="form-control" id="message" name="message" rows="5"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        Отправить сообщение
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection