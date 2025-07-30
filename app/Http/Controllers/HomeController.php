<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::withCount(['author', 'category', 'comments'])
            ->latest()
            ->paginate(9);

        $categories = Category::all();

        return view('home', compact('posts', 'categories'));
    }
}