@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-body p-5">
        <h1 class="card-title mb-4">О нашем блоге</h1>
        
        <p class="lead">Мы - команда энтузиастов, создающая качественный контент с 2023 года.</p>
        
        <h2 class="mt-5 mb-3">Наша миссия</h2>
        <p class="text-muted">Предоставлять полезную и актуальную информацию для наших читателей.</p>
        
        <h2 class="mt-5 mb-4">Команда</h2>
        <div class="row g-4">
            @foreach($teamMembers as $member)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <img src="{{ $member['avatar'] }}" 
                             class="rounded-circle mb-3" width="96" height="96">
                        <h3 class="h5">{{ $member['name'] }}</h3>
                        <p class="text-muted mb-0">{{ $member['role'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection