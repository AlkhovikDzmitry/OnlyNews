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
    return view('posts.show', compact('post'));
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
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published_at' => 'nullable|date|after_or_equal:now',
        ]);

        $post = new Post();
        $post->user_id = auth()->id();
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->category_id = $validated['category_id'];
        $post->author_id = Auth::id();
        $post->published_at = $validated['published_at'] ?? now();
        $post->reading_time = $this->calculateReadingTime($request->content);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
            $post->image = $imagePath;
        }

        $post->save();

        if (!empty($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        return redirect()->route('posts.show', $post->slug)
           ->with('success', 'Пост успешно создан!');
    }
    

    protected function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        return ceil($wordCount / 200);
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();
        
        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

       public function update(Request $request, $id)
{
    $post = Post::findOrFail($id);
    
    // Проверка прав
    $this->authorize('update', $post);
    
    
    // Валидация
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'tags' => 'sometimes|array',
        'tags.*' => 'exists:tags,id',
        'image' => $request->boolean('remove_image') ? 'nullable' : 'sometimes|image|mimes:jpeg,png,gif,webp|max:2048',
        'published_at' => 'nullable|date',
        'remove_image' => 'sometimes|boolean'
    ]);

    $this->handleImageUpdate($request, $post);


    $post->update([
        'title' => $validated['title'],
        'content' => $validated['content'],
        'category_id' => $validated['category_id'],
        //'published_at' => $validated['published_at'] ?? null,
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
        // Не возвращаемся тут, чтобы продолжить обработку нового изображения
    }

    // Если загружено новое изображение
    if ($request->hasFile('image')) {
        // Удаляем старое изображение если оно есть
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $path = $request->file('image')->store('posts', 'public');
        $post->image = $path;
        $post->save();
    }
}


    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('delete', $post);
        
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        
        $post->delete();
        return redirect()->route('profile')
               ->with('success', 'Пост успешно удалён!');
    }
}
