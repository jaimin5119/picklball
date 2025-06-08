<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;
    protected $dates = ['deleted_at'];
    
    protected $table = 'contact_us';
    protected $fillable = ['user_id','full_name','email','mobile','subject','message'];
}
