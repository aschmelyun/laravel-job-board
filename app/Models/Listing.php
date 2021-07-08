<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function clicks()
    {
        return $this->hasMany(Click::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeIsActive($builder, bool $status = true)
    {
        return $builder->where('is_active', $status);
    }

    public function scopeSearch($builder, $keyword)
    {
        return $builder->where(function ($q) use ($keyword) {
            $q->where('title', 'LIKE', '%' . $keyword . '%')
                ->orWhere('company', 'LIKE', '%' . $keyword . '%')
                ->orWhere('location', 'LIKE', '%' . $keyword . '%');
        });
    }

    public function scopeTagged($builder, $slug)
    {
        return $builder->whereHas('tags', function ($tag) use ($slug) {
            return $tag->whereSlug($slug);
        });
    }
}
