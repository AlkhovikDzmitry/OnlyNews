<?php
namespace App\Policies;
use App\Models\User;
use App\Models\Post;
class PostPolicy
{
    public function update(User $user, Post $post)
    {
        return $user->id === $post->user_id; // Проверка, является ли пользователь автором поста
    }
    public function delete(User $user, Post $post)
    {
        return $user->id === $post->user_id; // Проверка, является ли пользователь автором поста
    }
}