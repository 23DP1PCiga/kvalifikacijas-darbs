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

    public function reviews()
{
    return $this->hasMany(\App\Models\Review::class);
}
   
    public function boards()
{
    return $this->hasMany(\App\Models\Board::class);
}
}

