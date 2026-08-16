<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasSlug;

    public static function categoryOptions(): array
    {
        return [
            'news' => 'ข่าวสาร',
            'event' => 'กิจกรรม',
            'announcement' => 'ประกาศ',
        ];
    }

    protected $fillable = [
        'title',
        'slug',
        'category',
        'content',
        'image',
        'is_published',
        'published_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn ($model) => 'post-' . uniqid())
            ->saveSlugsTo('slug');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function images()
    {
        return $this->hasMany(PostImage::class)->orderBy('sort_order');
    }

    public function syncCoverImage(): void
    {
        $cover = $this->images()->where('is_cover', true)->first()
            ?? $this->images()->first();

        $this->updateQuietly(['image' => $cover?->image]);
    }
}