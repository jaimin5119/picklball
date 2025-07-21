<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledNotification extends Model
{
    use HasFactory;

    protected $table = 'scheduled_notifications';

    protected $fillable = [
        'title',
        'message',
        'target_audience',
        'status',
        'schedule_date',
        'schedule_time',
    ];
}
