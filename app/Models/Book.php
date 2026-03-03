<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'genre',
        'description',
        'cover',
        'publishing_year'
    ];

    public function ratings()
        {
            return $this->hasMany(\App\Models\Rating::class);
        }

    public function getAverageRatingAttribute()
        {
            return round($this->ratings()->avg('rating'), 1);
        }

    public function getRatingsCountAttribute()
        {
            return $this->ratings()->count();
        }
}

