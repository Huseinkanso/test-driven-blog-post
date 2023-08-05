<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
class Tag extends Model
{
    use HasFactory;

    protected $fillable=['name','slug'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($tag){
            $tag->slug=Str::slug($tag->name);
        });
    }

    public  function getRouteKeyName(): string
    {
        return 'slug';
    }
    public function blogs():BelongsToMany
    {
        return $this->belongsToMany(Blog::class);
    }

}
