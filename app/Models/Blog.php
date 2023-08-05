<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class Blog extends Model
{
    use HasFactory;

    protected $fillable=['title','body','published_at','image','user_id','slug'];

    // protected $casts=[
    //     'published_at'=>'datetime'
    //     ]
    // local query scope
    // like define a constraint function and use it to check when getting data
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    // define global query scope
    // protected static function booted()
    // {
    //     static::addGlobalScope('published',function(Builder $builder){
    //            $builder->whereNotNull('published_at');
    //     });
    // }


    // when we think of writing a function in model it is good to start writing unit test
    // write function to upload photo here instead of controller
    public function uploadImage($image)
    {
        // dd($image->getClientOriginalName());
        $name=$image->getClientOriginalName();
        Storage::disk('public')->put('blogs/'.$name,file_get_contents($image));
        $this->update(['image'=>$name]);
    }
    public function deleteImage($imageName)
    {
        // dd($imageName);
        Storage::disk('public')->delete('blogs/'.$imageName);
    }
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function store($request)
    {
        $blog=auth()->user()->blogs()->create($request->except('image'));

        $blog->uploadImage($request->image);
        $blog->tags()->attach($request->tag_ids);
    }
    public function edit($request)
    {
        // instead of those we can use sync
        // $blog->tags()->detach();
        // $blog->tags()->attach($request->tag_ids);
        if($request->image)
        {
            Storage::disk('public')->delete('blogs/'.$this->image);
            $this->uploadImage($request->image);
        }
        $this->update($request->except('image'));
        $this->tags()->sync($request->tag_ids);

    }
    // accessor => to manipulate the data from db before sending it
    public function getPublishedAtAttribute($value)
    {
        if($value)
        {
            return Carbon::parse($value)->format('Y-m-d\TH:m');
        }
    }
    public static function boot()
    {
        parent::boot();
        static::creating(function ($blog){
            $blog->slug=Str::slug($blog->title);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags() : BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
    public function tagIds()
    {
        return $this->tags->pluck('id')->toArray();
    }
}
