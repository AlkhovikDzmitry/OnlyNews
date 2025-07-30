<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{'OnlyNews'}}</title>
    <link rel="icon" href="{{ asset('favicon5.ico') }}" type="image/x-icon"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
        }
        .content {
            flex: 1 0 auto;
        }
        .footer {
            flex-shrink: 0;
        }
        .avatar-sm {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }
        .navbar-nav .nav-item {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Навбар -->
     
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
           <a class="navbar-brand text-primary fw-bold" href="{{ route('home') }}">OnlyNews</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">О нас</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contacts') }}">Контакты</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @if(auth()->check() && auth()->user()->is_admin)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.posts.index') }}">Модерация</a>
                        </li>
                    @endif
                    @auth
                        <li class="nav-item">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Аватар" class="avatar-sm me-2">
                            @else
                                <div class="avatar-sm me-2 bg-secondary d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-fill text-white"></i>
                                </div>
                            @endif
                            <a class="nav-link" href="{{ route('profile') }}">Профиль</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link">Выйти</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Вход</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Регистрация</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Основной контент -->
    <main class="content container my-5">
        @yield('content')
    </main>

    <!-- Футер -->
    <footer class="footer bg-white py-4 border-top">
        <div class="container text-center text-muted">
            © {{ date('Y') }} OnlyNews. Все права защищены.
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>