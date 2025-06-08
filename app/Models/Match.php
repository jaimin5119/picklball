<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;
    protected $table = 'matches';

    protected $fillable = [
        'match_id',
        'tournament_id',
        'match_start_date',
        'match_start_time',
        'team_a_id',
        'team_b_id',
        'location',
        'court_name',
        'scorer_id',
        'status',
        'team_one_score',
        'team_two_score',
        'winner_team_id',
        'service_team',
        'service_player',
        'match_end_reason',
    ];


}

