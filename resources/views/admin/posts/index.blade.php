@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Модерация постов</h1>
    
    <div class="card shadow-sm">
        <div class="card-body">
            @if($posts->isEmpty())
                <div class="alert alert-info">Нет постов для модерации</div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Название</th>
                                <th>Автор</th>
                                <th>Дата</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                            <tr>
                                <td>
                                    <a href="{{ route('posts.show', $post) }}" target="_blank">
                                        {{ $post->title }}
                                    </a>
                                </td>
                                <td>{{ $post->user->name }}</td>
                                <td>{{ $post->created_at->format('d.m.Y H:i') }}</td>
                                <td class="d-flex gap-2">
                                    <form method="POST" action="{{ route('admin.posts.approve', $post) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            Одобрить
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.posts.reject', $post) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Отклонить
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $posts->links() }}
            @endif
        </div>
    </div>
</div>
@endsection