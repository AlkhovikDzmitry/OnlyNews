<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    // Связи
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Аксессоры
    public function getPostCountAttribute()
    {
        return $this->posts()->count();
    }

    
}