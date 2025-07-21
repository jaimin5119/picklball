<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'target_audience', // 0 = All Users, 1 = Active Users, 2 = City
        'status',           // 0 = Unsent, 1 = Sent
    ];
}

