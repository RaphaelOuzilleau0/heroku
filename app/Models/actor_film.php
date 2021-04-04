<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class actor_film extends Model
{
    use HasFactory;

    protected $fillable = ['created_at', 'updated_at'];
}
