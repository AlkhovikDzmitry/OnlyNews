<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $query = $category->posts()
            ->with(['author', 'category', 'tags'])
            ->latest('published_at')
            ->where('published_at', '<=', now());

        // Неавторизованным и не-админам показываем только одобренные посты
        if (!Auth::check() || !Auth::user()->is_admin) {
            $query->where('status', Post::STATUS_APPROVED);
        }

        $posts = $query->paginate(9);

        return view('category.show', compact('category', 'posts'));
    }
}