<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $query = Post::withCount(['author', 'category', 'comments'])
            ->with(['author', 'category']); 

        // Если пользователь не администратор, показываем только одобренные посты
        if (!Auth::check() || !Auth::user()->is_admin) {
            $query->where('status', Post::STATUS_APPROVED);
        }

        $posts = $query->latest()
            ->paginate(9);

        $categories = Category::all();

        return view('home', compact('posts', 'categories'));
    }
}