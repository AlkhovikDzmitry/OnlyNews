<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['author', 'category', 'tags'])
            ->latest('published_at')
            ->where('published_at', '<=', now())
            ->paginate(9);

        $categories = Category::withCount('posts')->get();
        $popularTags = Tag::withCount('posts')->orderBy('posts_count', 'desc')->limit(10)->get();

        return view('posts.index', compact('posts', 'categories', 'popularTags'));
    }

    public function show(Post $post)
    {
        // Проверяем, может ли текущий пользователь просматривать пост
        if ($post->status !== Post::STATUS_APPROVED) {
            // Если пользователь не администратор И не автор поста - 404
            if (!auth()->check() || (auth()->id() !== $post->user_id && !auth()->user()->is_admin)) {
                abort(404);
            }

            // Для автора и админа добавляем информацию о статусе
            session()->flash('info', $this->getStatusMessage($post->status));
        }

        // Увеличиваем счетчик просмотров
        $post->increment('views');

        return view('posts.show', compact('post'));
    }

    private function getStatusMessage(string $status): string
    {
        return match ($status) {
            Post::STATUS_PENDING => 'Этот пост находится на модерации и пока не виден другим пользователям',
            Post::STATUS_REJECTED => 'Этот пост был отклонен модератором',
            default => '',
        };
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:posts,title',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published_at' => 'nullable|date|after_or_equal:now',
        ]);

        $post = new Post();
        $post->user_id = auth()->id();
        $post->author_id = Auth::id();
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->excerpt = $validated['excerpt'] ?? null;
        $post->category_id = $validated['category_id'];
        $post->status = Post::STATUS_PENDING;
        $post->published_at = $validated['published_at'] ?? now();
        $post->reading_time = $this->calculateReadingTime($request->content);
        $post->slug = Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts/thumbnails', 'public');
            $post->image = $imagePath;
        }

        $post->save();

        if (!empty($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Пост успешно создан и отправлен на модерацию. Он станет видимым после одобрения администратором.');
    }

    protected function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        return ceil($wordCount / 200);
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);

        // Проверка прав
        if (auth()->id() !== $post->user_id && !auth()->user()->is_admin) {
            abort(403);
        }

        $categories = Category::all();
        $tags = Tag::all();

        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // Проверка прав
        if (auth()->id() !== $post->user_id && !auth()->user()->is_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:tags,id',
            'image' => $request->boolean('remove_image') ? 'nullable' : 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published_at' => 'nullable|date',
            'remove_image' => 'sometimes|boolean'
        ]);

        $this->handleImageUpdate($request, $post);

        $post->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'] ?? null,
            'category_id' => $validated['category_id'],
            'slug' => Str::slug($validated['title']),
            'reading_time' => $this->calculateReadingTime($request->content),
        ]);

        if ($request->has('tags')) {
            $post->tags()->sync($validated['tags']);
        }

        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Пост успешно обновлен!');
    }

    protected function handleImageUpdate(Request $request, Post $post): void
    {
        // Если стоит галочка "удалить изображение" и у поста есть изображение
        if ($request->boolean('remove_image') && $post->image) {
            Storage::disk('public')->delete($post->image);
            $post->image = null;
            $post->save();
        }

        // Если загружено новое изображение
        if ($request->hasFile('image')) {
            // Удаляем старое изображение если оно есть
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $path = $request->file('image')->store('posts/thumbnails', 'public');
            $post->image = $path;
            $post->save();
        }
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if (auth()->id() !== $post->user_id && !auth()->user()->is_admin) {
            abort(403);
        }

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('profile')
            ->with('success', 'Пост успешно удалён!');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('posts/content', $filename, 'public');

            return response()->json([
                'url' => Storage::url($path)
            ]);
        }

        return response()->json(['error' => 'Ошибка загрузки изображения'], 400);
    }
}
