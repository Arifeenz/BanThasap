<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttractionImage extends Model
{
    protected $fillable = [
        'attraction_id',
        'image',
        'is_cover',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_cover' => 'boolean',
        ];
    }

    public function attraction()
    {
        return $this->belongsTo(Attraction::class);
    }

    protected static function booted(): void
    {
        static::saved(function (self $image) {
            if ($image->is_cover) {
                static::where('attraction_id', $image->attraction_id)
                    ->where('id', '!=', $image->id)
                    ->update(['is_cover' => false]);
            }

            $image->attraction?->syncCoverImage();
        });

        static::deleted(function (self $image) {
            $image->attraction?->syncCoverImage();
        });
    }
}
