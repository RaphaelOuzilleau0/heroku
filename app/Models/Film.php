<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['title', 'release_year', 'length', 'description', 'rating', 'language_id', 'special_features', 'image'];
}
