<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuthToken extends Model
{
    use HasFactory;
    protected $table = 'user_auth_tokens';

    protected $fillable = [
        'user_id',
        'email',
       	'token',

    ];
}
