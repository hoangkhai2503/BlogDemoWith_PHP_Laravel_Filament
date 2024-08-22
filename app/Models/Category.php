<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug'];
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'category_posts');
    }
    public function publishedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)
            ->where('active', '=', 1)
            ->whereDate('published_at', '<', Carbon::now());
    }
}
