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
        'views',

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
            $post->slug = $post->generateUniqueSlug($post->title);
            $post->excerpt = $post->excerpt ?: Str::limit(strip_tags($post->content), 150);
            $post->reading_time = $post->calculateReadingTime($post->content);
        });

        static::updating(function ($post) {
            if ($post->isDirty('title')) {
                $post->slug = $post->generateUniqueSlug($post->title);
            }
            if ($post->isDirty('content')) {
                $post->reading_time = $post->calculateReadingTime($post->content);
            }
        });
    }

    public static function calculateReadingTime(?string $content): int
    {
        $text = trim(strip_tags((string) $content));

        if ($text === '') {
            return 1;
        }

        // str_word_count не считает кириллицу — используем юникод-регулярку
        preg_match_all('/[\p{L}\p{N}]+/u', $text, $matches);

        return max(1, (int) ceil(count($matches[0]) / 200));
    }

    public function generateUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title) ?: Str::random(8);
        $slug = $baseSlug;
        $i = 1;

        while (static::where('slug', $slug)
            ->when($this->exists, fn ($q) => $q->whereKeyNot($this->getKey()))
            ->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        return $slug;
    }



    // Связи

     public function images()
    {
        return $this->hasMany(PostImage::class);
    }

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


    public function getContentAttribute($value)
    {
        // Декодируем HTML сущности
        $decoded = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return $decoded;
    }

    public function getReadingTimeTextAttribute()
    {
        return ($this->reading_time ?? 1) . ' мин. чтения';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getRouteKeyName()
    {
        return 'slug';
    }
}
