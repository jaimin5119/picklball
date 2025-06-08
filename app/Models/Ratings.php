<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ratings extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $table = 'ratings';
    protected $fillable = ['user_id', 'title', 'details', 'rating', 'display'];
}
