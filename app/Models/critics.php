<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class critics extends Model
{
    use HasFactory;

    protected $fillable = ['score', 'comment'];
}
