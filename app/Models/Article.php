<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array'
    ];

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(Like::class);
    }

    public function comments(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class);
    }
}
