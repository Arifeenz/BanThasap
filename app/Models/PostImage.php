<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    protected $fillable = [
        'post_id',
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

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    protected static function booted(): void
    {
        static::saved(function (self $image) {
            if ($image->is_cover) {
                static::where('post_id', $image->post_id)
                    ->where('id', '!=', $image->id)
                    ->update(['is_cover' => false]);
            }

            $image->post?->syncCoverImage();
        });

        static::deleted(function (self $image) {
            $image->post?->syncCoverImage();
        });
    }
}
