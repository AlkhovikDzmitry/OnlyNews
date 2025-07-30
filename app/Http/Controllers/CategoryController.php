<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $posts = $category->posts()
            ->with(['author', 'category', 'tags'])
            ->latest('published_at')
            ->where('published_at', '<=', now())
            ->paginate(9);

        return view('category.show', compact('category', 'posts'));
    }
}