<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile', [
            'user' => Auth::user(),
            'posts' => Auth::user()->posts()->latest()->get()
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'bio' => 'nullable|string|max:500',
        ]);

        Auth::user()->update($request->only('name', 'email', 'bio'));

        return back()->with('success', 'Профиль обновлен');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            
            // Удаляем старый аватар
            if (Auth::user()->avatar) {
                Storage::disk('public')->delete(Auth::user()->avatar);
            }

            Auth::user()->update(['avatar' => $path]);
        }

        return back()->with('success', 'Аватар обновлен');
    }
}