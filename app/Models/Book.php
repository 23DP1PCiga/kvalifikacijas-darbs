<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
  use HasFactory;
    protected $fillable = [
        'title',
        'author',
        'genre',
        'price',
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

