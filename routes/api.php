<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\UserRatingController;
use App\Http\Controllers\UserLoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/phone', [ApiController::class, 'sendOtp']);
Route::post('/verify-otp', [ApiController::class, 'verifyOtp']);
Route::post('/resend-otp', [ApiController::class, 'resendOtp']);
Route::post('/user-register', [ApiController::class, 'register']);
Route::post('/upload/image', [ApiController::class, 'uploadImage']);


Route::post('/get-player-list', [ApiController::class, 'getPlayerList']);
Route::post('/add-player', [ApiController::class, 'addPlayer']);
Route::post('/create-team', [ApiController::class, 'createTeam']);
Route::post('/team-list', [ApiController::class, 'teamList']);
Route::post('/team/details', [ApiController::class, 'teamDetails']);
Route::post('/team/remove-player', [ApiController::class, 'removePlayer']);


//tournament
Route::post('/tournament/create', [ApiController::class, 'createTournament']);
Route::post('/tournament/details', [ApiController::class, 'getTournamentDetails']);
Route::post('/tournament/add-teams', [ApiController::class, 'addTournamentTeams']);
Route::post('/tournament/add-players', [ApiController::class, 'addTournamentPlayers']);



// Create Match for Tournament (POST)
Route::post('match/create', [ApiController::class, 'createMatch']);
// Get Tournament Matches (post)
Route::post('tournament/matches', [ApiController::class, 'getTournamentMatches']);
Route::post('/tournament/remove-team', [ApiController::class, 'removeTeam']);


Route::post('/tournament/update-score', [ApiController::class, 'updateMatchScore']);
    Route::post('/tournament/match/status', [ApiController::class, 'getMatchStatus']);
    Route::post('/tournaments', [ApiController::class, 'getTournamentsByType']);
    Route::post('/tournaments/teams', [ApiController::class, 'getTournamentTeams']);
    Route::post('/tournaments/players', [ApiController::class, 'getTournamentPlayers']);
