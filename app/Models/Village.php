<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Village extends Model
{
    use HasSlug;

    protected $fillable = [
        'number',
        'name',
        'slug',
        'description',
        'highlight',
        'image',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn ($model) => 'village-' . $model->number)
            ->saveSlugsTo('slug');
    }

    public function attractions()
    {
        return $this->hasMany(Attraction::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}