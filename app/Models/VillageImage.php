<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VillageImage extends Model
{
    protected $fillable = [
        'village_id',
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

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    protected static function booted(): void
    {
        static::saved(function (self $image) {
            if ($image->is_cover) {
                static::where('village_id', $image->village_id)
                    ->where('id', '!=', $image->id)
                    ->update(['is_cover' => false]);
            }

            $image->village?->syncCoverImage();
        });

        static::deleted(function (self $image) {
            $image->village?->syncCoverImage();
        });
    }
}
