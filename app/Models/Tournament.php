<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

        protected $table = 'tournaments';
protected $fillable = [
        'tournament_id',
        'tournament_name',
        'tournament_logo',
        'tournament_banner',
        'start_date',
        'end_date',
        'description',
        'organizer_name',
        'organizer_phone',
        'location',
        'match_type',
        'match_point_format',
        'tournament_type',
        'tournament_format',
        'no_of_teams',
        'no_of_groups',
        'teams_per_group',
        'knockout_round',
        'seeding_type',
        'user_id',
        'team_id',
    ];

    // If you don't use auto-incrementing numeric ID
    public $incrementing = true;

}
