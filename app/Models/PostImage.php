<?php

// app/Models/PostImage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    protected $fillable = ['post_id', 'path', 'order'];
    
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
}