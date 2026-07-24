<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'story',
        'price',
        'unit',
        'contact',
        'image',
        'village_id',
        'is_active',
        'is_featured',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn ($model) => 'product-' . uniqid())
            ->saveSlugsTo('slug');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function syncCoverImage(): void
    {
        $cover = $this->images()->where('is_cover', true)->first()
            ?? $this->images()->first();

        $this->updateQuietly(['image' => $cover?->image]);
    }
}