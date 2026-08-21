@auth
    @isset($viewedPosts)
        @if($viewedPosts->isNotEmpty())
            <section class="mt-5 pt-4 border-top">
                <h2 class="h4 mb-4">
                    <i class="bi bi-clock-history me-2"></i>Вы смотрели
                </h2>

                <div class="row g-3">
                    @foreach($viewedPosts as $viewedPost)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card h-100 shadow-sm">
                                <a href="{{ route('posts.show', $viewedPost->slug) }}" class="text-decoration-none">
                                    <img src="{{ $viewedPost->image_url }}"
                                         class="card-img-top" alt="{{ $viewedPost->title }}"
                                         style="height: 120px; object-fit: cover;">
                                </a>
                                <div class="card-body p-2 d-flex flex-column">
                                    <a href="{{ route('posts.show', $viewedPost->slug) }}"
                                       class="text-decoration-none text-dark fw-medium small mb-2">
                                        {{ Str::limit($viewedPost->title, 60) }}
                                    </a>
                                    <small class="text-muted mt-auto">
                                        <i class="bi bi-eye me-1"></i>{{ $viewedPost->views }}
                                        <span class="mx-1">·</span>
                                        {{ \Carbon\Carbon::parse($viewedPost->pivot->viewed_at)->format('d.m.Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    @endisset
@endauth
