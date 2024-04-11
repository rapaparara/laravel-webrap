<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Cviebrock\EloquentSluggable\Sluggable;

class Posts extends Model
{
    use Sluggable;
    use HasFactory;
    protected $fillable = [
        'title',
        'user_id',
        'slug',
        'content',
        'image',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'maxLengthKeepWords' => 100
            ]
        ];
    }
}
