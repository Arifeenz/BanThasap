<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Attraction extends Model
{
    use HasSlug;

    public static function typeOptions(): array
    {
        return [
            'nature' => 'ธรรมชาติ',
            'history' => 'ประวัติศาสตร์',
            'learning' => 'แหล่งเรียนรู้',
            'community' => 'ชุมชน',
        ];
    }

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'how_to_get',
        'open_hours',
        'contact',
        'image',
        'village_id',
        'latitude',
        'longitude',
        'is_active',
        'is_featured',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn ($model) => 'attraction-' . uniqid())
            ->saveSlugsTo('slug');
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function images()
    {
        return $this->hasMany(AttractionImage::class)->orderBy('sort_order');
    }

    public function syncCoverImage(): void
    {
        $cover = $this->images()->where('is_cover', true)->first()
            ?? $this->images()->first();

        $this->updateQuietly(['image' => $cover?->image]);
    }
}