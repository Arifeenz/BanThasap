<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Attraction extends Model
{
    use HasSlug;

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
}