<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'image',
        'category_id',
        'author_id',
        'published_at',
        'reading_time',
        'user_id',
        'status',
        
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $attributes = [
        'status' => self::STATUS_PENDING
    ];

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    protected $casts = [
        'published_at' => 'datetime',
        'reading_time' => 'integer',
    ];

    // Автоматическое создание slug при сохранении
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->slug = Str::slug($post->title);
            $post->excerpt = Str::limit(strip_tags($post->content), 150);
            $post->reading_time = max(1, round(str_word_count(strip_tags($post->content)) / 200));
        });

        static::updating(function ($post) {
            $post->slug = Str::slug($post->title);
            $post->excerpt = Str::limit(strip_tags($post->content), 150);
            $post->reading_time = max(1, round(str_word_count(strip_tags($post->content)) / 200));
        });

        
    }

    // Связи

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }


    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // Аксессоры
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : 'https://via.placeholder.com/800x400';
    }

    public function getReadingTimeTextAttribute()
    {
        return $this->reading_time . ' мин. чтения';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getReadingTimeAttribute()
{
    // Пример: считаем слова в основном тексте или контенте поста
    $wordCount = str_word_count(strip_tags($this->content));

    // Средняя скорость чтения: 200 слов в минуту
    $minutes = ceil($wordCount / 200);

    return $minutes;
}



    }