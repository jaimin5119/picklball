<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAuthToken;
use Illuminate\Support\Str;
use Validator;
use Illuminate\Http\Request;
use App\Models\OtpVerification;
use Illuminate\Support\Facades\Mail;
use Hash;
use App\Models\Faq;
use App\Models\CmsPages;
use App\Models\ContactUs;
use App\Models\Admin;
use App\Models\Team;
use App\Models\TeamPlayer;
use DB;
use App\Models\UserNotification;
use App\Models\Notifications;
use App\Models\Vistor;
use Illuminate\Support\Facades\Cache;
use App\Models\Tournament;
use Carbon\Carbon;
use App\Models\Match;



class ApiController extends Controller
{
    public function sendOtp(Request $request)
{
    $validator = Validator::make($request->all(), [
        'phone' => 'required|string|regex:/^\+?\d{10,15}$/',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'code' => 400,
            'message' => 'Invalid phone number',
            'data' => (object)[]
        ]);
    }

    // $otp = rand(100000, 999999);

    $otp = 111111;
    Cache::put('otp_' . $request->phone, $otp, now()->addMinutes(5));

    // Generate a random 64-character token
    $pass = Str::random(64);
// verifyOtp
    return response()->json([
        'code' => 200,
        'message' => 'OTP sent successfully',
        'data' => [
            'sent' => true,
            'otp' => $otp
        ]
    ]);
}


    public function verifyOtp(Request $request)
{
    $request->validate([
        'phone' => 'required|string',
        'otp' => 'required|string',
    ]);

    $cachedOtp = Cache::get('otp_' . $request->phone);

    if ($cachedOtp != $request->otp) {
        return response()->json(['code' => 400, 'message' => 'Invalid OTP', 'data' => (object)[]]);
    }

    $user = User::where('mobile', $request->phone)->first();
    $isNewUser = false;

    if (!$user) {
        $user = User::create([
            'mobile' => $request->phone,
            'is_active' => 1,
            'mobile_verified' => 1,
        ]);
        $isNewUser = true;
    }

    $accessToken = Str::random(64);

    // Store or update token
    UserAuthToken::updateOrCreate(
        ['user_id' => $user->id],
        ['token' => $accessToken]
    );

    return response()->json([
        'code' => 200,
        'message' => 'OTP verified successfully',
        'data' => [
            'userId'=>$user->id,
            'token' => $accessToken,
            'isNewUser' => $isNewUser
        ]
    ]);
}


   public function resendOtp(Request $request)
{
    $request->validate(['phone' => 'required|string']);

    $otp = rand(100000, 999999);
    Cache::put('otp_' . $request->phone, $otp, now()->addMinutes(5));

    // Generate a random 64-character token
    $pass = Str::random(64);

    return response()->json([
        'code' => 200,
        'message' => 'OTP resent successfully',
        'data' => [
            'otp' => $otp
        ]
    ]);
}


   public function register(Request $request)
{
    $user = User::where('mobile', $request->phone)->first();

    if (!$user) {
        return response()->json([
            'code' => 400,
            'message' => 'User not verified by OTP',
            'data' => (object)[],
        ]);
    }

    // Image upload logic
    // $imagePath = $user->image;
    // if ($request->hasFile('profileImage')) {
    //     $image = $request->file('profileImage');
    //     $filename = time() . '.' . $image->getClientOriginalExtension();
    //     $folderPath = public_path('uploads/' . $user->id);

    //     if (!file_exists($folderPath)) {
    //         mkdir($folderPath, 0755, true);
    //     }

    //     $image->move($folderPath, $filename);
    //     $imagePath = 'uploads/' . $user->id . '/' . $filename;
    // }

    $user->update([
        'first_name' => $request->fullName,
        'email'      => $request->email,
        'location'   => $request->location,
        'dob'        => $request->dob,
        'gender'     => $request->gender,
        'image'      => $request->profileImage,
    ]);

    $token = UserAuthToken::where('user_id', $user->id)->value('token');

    return response()->json([
        'code' => 200,
        'message' => 'Profile created successfully',
        'data' => [
            'userId'       => $user->id,
            'token'        => $token,
            'fullName'     => $user->first_name,
            'email'        => $user->email,
            'location'     => $user->location,
            'dob'          => $user->dob,
            'gender'       => $user->gender,
            'profileImage' => $user->image,
            'phone'        => $user->mobile
        ]
    ]);
}


public function authCheck($token, $user_id) {
    $resp = [];
    
    $user = User::find($user_id);
    if (!$user || $user->is_active == 0) {
        return [
            'code' => 401,
            'status' => 'error',
            'message' => 'This user account is blocked. Please contact Admin.'
        ];
    }

    if (empty($token)) {
        return [
            'code' => 401,
            'status' => 'error',
            'message' => 'Unauthorized! Access denied.'
        ];
    }

    $access_token = UserAuthToken::where('user_id', $user_id)->value('token');
    if ($token !== $access_token) {
        return [
            'code' => 401,
            'status' => 'error',
            'message' => 'Invalid token or token expired'
        ];
    }

    return []; // Success (no error)
}
public function getPlayerList(Request $request) {
    $user_id = $request->userId;
    $token = $request->header('token');

    $authcheck = $this->authCheck($token, $user_id);
    if (!empty($authcheck)) {
        return response()->json($authcheck);
    }

    $searchKey = $request->input('searchKey');
    $perPage = 10;
    $page = $request->input('page', 1);

    $query = User::when($searchKey, function($query) use ($searchKey) {
        $query->where('first_name', 'like', "%$searchKey%")
              ->orWhere('mobile', 'like', "%$searchKey%");
    });

    $totalRecords = $query->count();
    $players = $query->select('id as userId', 'first_name as username', 'image', 'mobile')
                     ->offset(($page - 1) * $perPage)
                     ->limit($perPage)
                     ->get();


    // Convert image path to full URL
    $players = $players->map(function($player) {
                $player->userId = (string) $player->userId;
        $player->mobile = (string) $player->mobile;

        $player->image = $player->image ? asset($player->image) : asset('default.png'); // fallback if image not available
        return $player;
    });

    $totalPages = ceil($totalRecords / $perPage);

    return response()->json([
        'code' => 200,
        'message' => $players->count() ? 'Player list fetched successfully' : 'No players found',
        'totalRecords' => $totalRecords,
        'totalPages' => $totalPages,
        'data' => $players
    ]);
}

public function addPlayer(Request $request)
{
    // Extract userId and token from request
    $user_id = $request->userId;
    $token = $request->header('token');

    // Run your auth check method
    $authcheck = $this->authCheck($token, $user_id);

    // If authcheck returns something (error), return that response immediately
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401); // 401 Unauthorized or your auth error code
    }

    // Validate request input with custom messages
    $validator = \Validator::make($request->all(), [
        'fullName' => 'required|string',
        'mobileNumber' => 'required|string|unique:users,mobile',
    ], [
        'fullName.required' => 'Full name is required.',
        'mobileNumber.required' => 'Mobile number is required.',
        'mobileNumber.unique' => 'This mobile number is already in use.',
    ]);

      if ($validator->fails()) {
    return response()->json([
        'code' => 422,
        'status' => 'error',
        'message' => $validator->errors()->first(),
    ], 422);
}




    // Create user
    $user = User::create([
        'first_name' => $request->fullName,
        'mobile' => $request->mobileNumber,
        'image' => null
    ]);

    // Return success response with created user info
    return response()->json([
        'code' => 200,
        'message' => 'Player added successfully',
        'data' => [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'mobile' => $user->mobile,
            'image' => $user->image
        ]
    ]);
}


public function createTeam(Request $request)
{
    // Auth Check
    $user_id = $request->userId;
    $token = $request->header('token');

    $authcheck = $this->authCheck($token, $user_id);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    // Validation with custom error format
    $validator = \Validator::make($request->all(), [
        'teamName' => 'required|string',
        'teamLogo' => 'nullable|url',
        'players' => 'required|array|min:1',
'admin' => 'required|integer|exists:users,id',
    ], [
        'teamName.required' => 'Team name is required.',
        'players.required' => 'At least one player is required.',
        'admin.exists' => 'Admin user does not exist.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'code' => 422,
            'status' => 'error',
            'message' => $validator->errors()->first(),
        ], 422);
    }

    // Create Team
    $team = Team::create([
        'teamName' => $request->teamName,
        'teamLogo' => $request->teamLogo,
        'adminId' => $request->admin
    ]);

    // Attach players to team using `team_id` (numeric ID)
    foreach ($request->players as $userId) {
        TeamPlayer::create([
            'team_id' => $team->id, // This is the correct foreign key
            'userId' => $userId
        ]);
    }

    // Success response
    return response()->json([
        'code' => 200,
        'status' => 'success',
        'message' => 'Team created successfully',
        'data' => [
            'teamId' => $team->id
        ]
    ]);
}
public function teamList(Request $request)
{
    $user_id = $request->userId;
    $token = $request->header('token');


    // Auth check (your existing method)
    $authcheck = $this->authCheck($token, $user_id);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    $perPage = 10;
    $page = $request->input('page');

    // Get total number of teams
    $totalRecords = DB::table('teams')->count();

    // Get paginated teams
    $teams = DB::table('teams')
        ->select('id', 'teamName', 'teamLogo', 'adminId')
        ->offset(($page - 1) * $perPage)
        ->limit($perPage)
        ->get();

    $teamIds = $teams->pluck('id')->toArray();

    // Get players of these teams
    $players = DB::table('team_players')
        ->join('users', 'team_players.userId', '=', 'users.id')
        ->whereIn('team_players.team_id', $teamIds)
        ->select(
            'team_players.team_id',
            'users.id as playerId',
            'users.first_name as playerName',
            'users.image as playerProfileUrl'
        )
        ->get();

    // Group players by team_id
    $playersGrouped = $players->groupBy('team_id');

    // Attach players to teams
    $teamsNested = $teams->map(function ($team) use ($playersGrouped) {
        return [
            'teamId' => $team->id,
            'teamName' => $team->teamName,
            'teamLogo' => $team->teamLogo ? asset($team->teamLogo) : '',
            'adminId' => $team->adminId,
            'players' => isset($playersGrouped[$team->id]) ? $playersGrouped[$team->id]->map(function ($player) {
                return [
                    'playerId' => $player->playerId,
                    'playerName' => $player->playerName,
                    'playerProfileUrl' => $player->playerProfileUrl ? asset($player->playerProfileUrl) : '',
                ];
            })->values() : []
        ];
    });

    $totalPages = ceil($totalRecords / $perPage);

    return response()->json([
        'code' => 200,
        'message' => $teamsNested->count() ? 'Team list fetched successfully' : 'No teams found',
        'totalRecords' => $totalRecords,
        'totalPages' => $totalPages,
        'data' => $teamsNested,
    ]);
}

public function teamDetails(Request $request)
{
    $user_id = $request->userId;
    $token = $request->header('token');

    // Auth check (your existing method)
    $authcheck = $this->authCheck($token, $user_id);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    $request->validate([
        'teamId' => 'required|integer|exists:teams,id'  // Assuming teamId is numeric 'id'
    ]);

    $teamId = $request->teamId;

    // Get the team with admin info
    $team = DB::table('teams')
        ->join('users as admin', 'teams.adminId', '=', 'admin.id')
        ->where('teams.id', $teamId)
        ->select(
            'teams.id as teamId',
            'teams.teamName',
            'teams.teamLogo',
            'admin.id as adminId',
            'admin.id as adminUserId',
            'admin.first_name as adminName',
            'admin.mobile as adminMobile'
        )
        ->first();

    if (!$team) {
        return response()->json([
            'code' => 404,
            'message' => 'Team not found',
            'data' => ''
        ]);
    }

    // Get all players of the team
    $players = DB::table('team_players')
        ->join('users', 'team_players.userId', '=', 'users.id')
        ->where('team_players.team_id', $teamId)
        ->select(
            'users.id as playerId',
            'users.id as playerUserId',
            'users.first_name as playerName',
            'users.image as playerProfileUrl',
            'users.mobile as playerMobile'
        )
        ->get();

    // Fix player profile URLs
    $players = $players->map(function ($player) {
        if ($player->playerProfileUrl === null || $player->playerProfileUrl === '') {
            $player->playerProfileUrl = '';
        } else {
            $player->playerProfileUrl = asset($player->playerProfileUrl);
        }
        return $player;
    });

    // Format response 
    $result = [
        'teamId' => $team->teamId,
        'teamName' => $team->teamName,
        'teamLogo' => $team->teamLogo,
        'admin' => [
            'adminId' => $team->adminId,
            'userId' => $team->adminUserId,
            'fullName' => $team->adminName,
            'mobileNumber' => $team->adminMobile,
        ],
        'players' => $players,
    ];

    return response()->json([
        'code' => 200,
        'message' => 'Team details fetched successfully',
        'data' => $result,
    ]);
}

public function removePlayer(Request $request)
{
    $user_id = $request->userId;
    $token = $request->header('token');

    // Auth check
    $authcheck = $this->authCheck($token, $user_id);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    

    // Check if team exists
    $team = Team::find($request->teamId);
    if (!$team) {
        return response()->json([
            'code' => 404,
            'message' => 'Team not found',
            'data' => ''
        ]);
    }

    // Prevent admin from removing themselves
    if ((int)$team->adminId === (int)$request->userId) {
        return response()->json([
            'code' => 403,
            'message' => 'Admin cannot remove themselves',
            'data' => ''
        ]);
    }

    // Check if the player exists in the team
    $player = TeamPlayer::where('team_id', $request->teamId)
                        ->where('userId', $request->userId)
                        ->first();

    if (!$player) {
        return response()->json([
            'code' => 404,
            'message' => 'Player not found in the team',
            'data' => ''
        ]);
    }

    // Remove the player
    $player->delete();

    return response()->json([
        'code' => 200,
        'message' => 'User removed from team successfully',
        'data' => ''
    ]);
}

public function createTournament(Request $request)
{
    $user_id = $request->userId;
    $token = $request->header('token');

    // Auth check
    $authcheck = $this->authCheck($token, $user_id);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    $tournamentInfo = $request->input('tournamentInfo');
    $matchFormat = $request->input('matchFormat');
    $config = $request->input('tournamentConfig');

    $tournament_id = 'tournament_' . Str::random(8);

    // Handle tournament type fields conditionally
    $no_of_teams = null;
    $no_of_groups = null;
    $teams_per_group = null;

    if ($config['tournamentType'] === 'Knockout') {
        $no_of_teams = $config['noOfTeams'] ?? null;
    } elseif ($config['tournamentType'] === 'Group+Knockout') {
        $no_of_groups = $config['noOfGroups'] ?? null;
        $teams_per_group = $config['teamsPerGroup'] ?? null;
    }

    $tournament = Tournament::create([
        'tournament_id'        => $tournament_id,
        'tournament_name'      => $tournamentInfo['tournamentName'],
        'tournament_logo'      => $tournamentInfo['tournamentLogo'] ?? null,
        'tournament_banner'    => $tournamentInfo['tournamentBanner'] ?? null,
        'start_date'           => Carbon::parse($tournamentInfo['startDate'])->format('Y-m-d H:i:s'),
        'end_date'             => Carbon::parse($tournamentInfo['endDate'])->format('Y-m-d H:i:s'),
        'description'          => $tournamentInfo['description'] ?? null,
        'organizer_name'       => $tournamentInfo['organizerName'],
        'organizer_phone'      => $tournamentInfo['organizerPhone'],
        'location'             => $tournamentInfo['location'],

        'match_type'           => $matchFormat['matchType'],
        'match_point_format'   => $matchFormat['matchPointFormat'],

        'tournament_type'      => $config['tournamentType'],
        'tournament_format'    => $config['tournamentFormat'],
        'no_of_teams'          => $no_of_teams,
        'no_of_groups'         => $no_of_groups,
        'teams_per_group'      => $teams_per_group,
        'knockout_round'       => $config['KnockOutRound'] ?? null,
        'seeding_type'         => $config['seedingType'],

        'user_id'              => $user_id,
        // 'team_id'              => $request->teamId ?? null,
    ]);

    return response()->json([
        'code' => 200,
        'message' => 'Tournament created successfully',
        'data' => [
            'tournament_id' => $tournament->tournament_id
        ]
    ]);
}

public function getTournamentDetails(Request $request)
{
    try {
        $user_id = $request->userId;
        $token = $request->header('token');
$tournamentId = $request->input('tournamentId');

        // Auth check
          // Auth check
    $authcheck = $this->authCheck($token, $user_id);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

        if (!$tournamentId) {
            return response()->json([
                'code' => 400,
                'message' => 'tournamentId parameter is required',
                'result' => ''
            ], 400);
        }

        $tournament = Tournament::where('tournament_id', $tournamentId)->first();

        if (!$tournament) {
            return response()->json([
                'code' => 404,
                'message' => 'Tournament not found',
                'result' => ''
            ], 404);
        }

        $response = [
            'tournamentInfo' => [
                'tournamentId'      => $tournament->tournament_id,
                'tournamentName'    => $tournament->tournament_name,
                'tournamentLogo'    => $tournament->tournament_logo,
                'tournamentBanner'  => $tournament->tournament_banner,
                'startDate'         => Carbon::parse($tournament->start_date)->toIso8601String(),
                'endDate'           => Carbon::parse($tournament->end_date)->toIso8601String(),
                'description'       => $tournament->description,
                'organizerName'     => $tournament->organizer_name,
                'organizerPhone'    => $tournament->organizer_phone,
                'location'          => $tournament->location,
            ],
            'matchFormat' => [
                'matchType'         => $tournament->match_type,
                'matchPointFormat'  => $tournament->match_point_format,
                'bestOf'            => $tournament->best_of ?? '3', // Default fallback
            ],
            'tournamentConfig' => [
                'tournamentType'     => $tournament->tournament_type,
                'tournamentFormat'   => $tournament->tournament_format,
                'seedingType'        => $tournament->seeding_type,
                'numberOfTeams'      => (string)($tournament->no_of_teams ?? ''),
                'knockoutStartRound' => $tournament->knockout_round ?? '',
            ]
        ];

        return response()->json([
            'code' => 200,
            'message' => 'Tournament details fetched successfully',
            'data' => $response
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'code' => 500,
            'message' => 'Something went wrong',
            'data' => $e->getMessage()
        ], 500);
    }
}



// public function createMatch(Request $request)
// {
//     try {
//         $user_id = $request->userId;
//         $token = $request->header('token');

//         // Auth check
//         $authcheck = $this->authCheck($token, $user_id);
//         if (!empty($authcheck)) {
//             return response()->json($authcheck, 401);
//         }

//         // ✅ Fix validation
//         $validated = $request->validate([
//             'tournamentId'    => 'required|string|exists:tournaments,tournament_id',
//             'matchStartDate'  => 'required|date',
//             'matchStartTime'  => 'required|date_format:H:i:s',
//             'teamAId'         => 'required|integer|exists:teams,id',
//             'teamBId'         => 'required|integer|exists:teams,id',
//             'location'        => 'required|string',
//             'courtName'       => 'nullable|string',
//             'Scorer_id'       => 'nullable|string',
//         ]);

//         $match_id = 'match_' . Str::random(8);

//         $match = Match::create([
//             'match_id'        => $match_id,
//             'tournament_id'   => $validated['tournamentId'],
//             'match_date'      => Carbon::parse($validated['matchStartDate'])->format('Y-m-d'),
//             'match_time'      => $validated['matchStartTime'],
//             'team_a_id'       => $validated['teamAId'],
//             'team_b_id'       => $validated['teamBId'],
//             'location'        => $validated['location'],
//             'court_name'      => $validated['courtName'] ?? null,
//             'scorer_id'       => $validated['Scorer_id'] ?? null,
//             'status'          => 'Pending',
//             'created_by'      => $user_id,
//         ]);

//         return response()->json([
//             'code' => 200,
//             'message' => 'Match created successfully',
//             'data' => [
//                 'matchId' => $match->match_id
//             ]
//         ], 200);

//     } catch (ValidationException $ve) {
//         return response()->json([
//             'code' => 422,
//             'message' => 'Validation failed',
//             'data' => $ve->errors(),
//         ], 422);
//     } catch (\Exception $e) {
//         return response()->json([
//             'code' => 500,
//             'message' => 'Something went wrong',
//             'data' => $e->getMessage(),
//         ], 500);
//     }
// }
public function createMatch(Request $request)
{
    try {
        $user_id = $request->userId;
        $token = $request->header('token');

        // Auth check
        $authcheck = $this->authCheck($token, $user_id);
        if (!empty($authcheck)) {
            return response()->json($authcheck, 401);
        }

        // Validation
        $validated = $request->validate([
            'tournamentId'    => 'required|string|exists:tournaments,tournament_id',
            'matchStartDate'  => 'required|date',
            'matchStartTime'  => 'required|date_format:H:i:s',
            'teamAId'         => 'required|integer|exists:teams,id',
            'teamBId'         => 'required|integer|exists:teams,id',
            'location'        => 'required|string',
            'courtName'       => 'nullable|string',
            'Scorer_id'       => 'nullable|string',
        ]);

        $match_id = 'match_' . Str::random(8);

        // Insert into the database using query builder
        DB::table('matches')->insert([
            'match_id'        => $match_id,
            'tournament_id'   => $validated['tournamentId'],
            'match_start_date'      => Carbon::parse($validated['matchStartDate'])->format('Y-m-d'),
            'match_start_time'      => $validated['matchStartTime'],
            'team_a_id'       => $validated['teamAId'],
            'team_b_id'       => $validated['teamBId'],
            'location'        => $validated['location'],
            'court_name'      => $validated['courtName'] ?? null,
            'scorer_id'       => $validated['Scorer_id'] ?? null,
            'status'          => 'Pending',
            // 'created_by'      => $user_id,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Match created successfully',
            'data' => [
                'matchId' => $match_id
            ]
        ], 200);

    } catch (ValidationException $ve) {
        return response()->json([
            'code' => 422,
            'message' => 'Validation failed',
            'data' => $ve->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'code' => 500,
            'message' => 'Something went wrong',
            'data' => $e->getMessage(),
        ], 500);
    }
}

public function getTournamentMatches(Request $request)
{
    try {
        $user_id = $request->userId;
        $token = $request->header('token');
        $tournamentId = $request->input('tournamentId');

        // Auth check
        $authcheck = $this->authCheck($token, $user_id);
        if (!empty($authcheck)) {
            return response()->json($authcheck, 401);
        }

        if (!$tournamentId) {
            return response()->json([
                'code' => 400,
                'message' => 'tournamentId parameter is required',
                'result' => ''
            ], 400);
        }

        // Check if tournament exists
        $tournament = Tournament::where('tournament_id', $tournamentId)->first();
        if (!$tournament) {
            return response()->json([
                'code' => 404,
                'message' => 'Tournament not found',
                'result' => ''
            ], 404);
        }

        // Fetch matches using JOIN to get team names and logos
      $matches = DB::table('matches')
    ->join('teams as team_a', 'matches.team_a_id', '=', 'team_a.id')
    ->join('teams as team_b', 'matches.team_b_id', '=', 'team_b.id')
    ->where('matches.tournament_id', $tournamentId)
    ->select(
        'matches.*',
        'team_a.teamName as teamAName',
        'team_a.teamLogo as teamALogo',
        'team_b.teamName as teamBName',
        'team_b.teamLogo as teamBLogo'
    )
    ->orderBy('matches.match_start_date')
    ->orderBy('matches.match_start_time')
    ->get();



        $result = [];
        $matchNumber = 1;

        foreach ($matches as $match) {
            $result[] = [
                'matchNumber' => $matchNumber++,
                'matchDate' => Carbon::parse($match->match_start_date)->format('Y-m-d'),
                'matchTimeUTC' => Carbon::parse($match->match_start_date . ' ' . $match->match_start_time)->toIso8601String(),
                'status' => $match->status,
                'location' => $match->location,
                'courtName' => $match->court_name,
                'teamA' => [
                    'teamId' => $match->team_a_id,
                    'teamName' => $match->teamAName,
                    'teamLogo' => $match->teamALogo,
                ],
                'teamB' => [
                    'teamId' => $match->team_b_id,
                    'teamName' => $match->teamBName,
                    'teamLogo' => $match->teamBLogo,
                ],
            ];
        }

        return response()->json([
            'code' => 200,
            'message' => 'Tournament matches fetched successfully',
            'data' => $result,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'code' => 500,
            'message' => 'Something went wrong',
            'data' => $e->getMessage(),
        ], 500);
    }
}
public function removeTeam(Request $request)
{
    try {
         $user_id = $request->userId;
        $token = $request->header('token');

        // Auth check
        $authcheck = $this->authCheck($token, $user_id);
        if (!empty($authcheck)) {
            return response()->json($authcheck, 401);
        }

        // Validate input with integer IDs
        $validated = $request->validate([
            'tournamentId' => 'required|integer|exists:tournaments,id',
            'teamId' => 'required|integer|exists:teams,id',
        ]);

        // Delete team from tournament pivot table
        $removed = DB::table('tournament_teams')
            ->where('tournament_id', $validated['tournamentId'])
            ->where('team_id', $validated['teamId'])
            ->delete();

        if ($removed) {
            return response()->json([
                'code' => 200,
                'message' => 'Team removed from tournament successfully',
                'result' => (object)[]
            ], 200);
        } else {
            return response()->json([
                'code' => 404,
                'message' => 'Team not found in this tournament',
                'result' => (object)[]
            ], 404);
        }

    } catch (ValidationException $e) {
        return response()->json([
            'code' => 422,
            'message' => 'Validation failed',
            'result' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'code' => 500,
            'message' => 'Something went wrong',
            'result' => $e->getMessage()
        ], 500);
    }
}

// public function updateMatchScore(Request $request)
// {
//     // Required: User ID and Token from request
//     $user_id = $request->userId;
//     $token = $request->header('token');

//     // Auth check
//     $authcheck = $this->authCheck($token, $user_id);
//     if (!empty($authcheck)) {
//         return response()->json($authcheck, 401);
//     }

//     // Fetch and update match
//     $match = DB::tabel('matches')->where('match_id', $request->matchId)->firstOrFail();

//     $match->team_one_score = $request->teamOneScore;
//     $match->team_two_score = $request->teamTwoScore;
//     $match->service_team = $request->get('Service team');
//     $match->service_player = $request->get('Service player');
//     $match->match_end_reason = $request->get('matchEndReason');
//     $match->winner_team_id = $request->winnerTeamId;
//     $match->status = 'Completed';
//     $match->save();

//     return response()->json([
//         'code' => 200,
//         'message' => 'Score updated successfully',
//         'data' => [
//             'matchId' => $match->match_id,
//             'status' => $match->status
//         ]
//     ]);
// }
public function updateMatchScore(Request $request)
{
    $user_id = $request->userId;
    $token = $request->header('token');

    // Auth check
    $authcheck = $this->authCheck($token, $user_id);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    // Validate scores
    $teamOne = (int) $request->teamOneScore;
    $teamTwo = (int) $request->teamTwoScore;

    // Game not over yet
    $maxPoint = max($teamOne, $teamTwo);

    // Check if game ended properly
    if ($maxPoint < 11) {
        return response()->json([
            'code' => 400,
            'message' => 'Game must reach at least 11 points to be completed.'
        ]);
    }

    $scoreDiff = abs($teamOne - $teamTwo);

    if ($teamOne >= 10 && $teamTwo >= 10) {
        // Deuce rule: must win by 2 points, up to 13
        if ($maxPoint < 14) {
            if ($scoreDiff < 2) {
                return response()->json([
                    'code' => 400,
                    'message' => 'At 10-10 or above, a team must win by 2 points (until 13).'
                ]);
            }
        } elseif ($maxPoint == 14 && $scoreDiff < 1) {
            return response()->json([
                'code' => 400,
                'message' => 'At 13-13, next point wins. Score must be 14-13 or 13-14.'
            ]);
        } elseif ($maxPoint > 14) {
            return response()->json([
                'code' => 400,
                'message' => 'Maximum score limit reached. 14 is the cap.'
            ]);
        }
    } else {
        // Regular rule: must win by 2 if below 10-10
        if ($scoreDiff < 2) {
            return response()->json([
                'code' => 400,
                'message' => 'A team must win by 2 points.'
            ]);
        }
    }

    // Proceed to fetch and update match
    $match = DB::tabel('matches')->where('match_id', $request->matchId)->firstOrFail();

    // Check for duplicate values
    $isSame = (
        $match->team_one_score == $teamOne &&
        $match->team_two_score == $teamTwo &&
        $match->service_team == $request->get('serviceTeam') &&
        $match->service_player == $request->get('servicePlayer') &&
        $match->match_end_reason == $request->get('matchEndReason') &&
        $match->winner_team_id == $request->winnerTeamId &&
        $match->status == 'Completed'
    );

    if ($isSame) {
        return response()->json([
            'code' => 200,
            'message' => 'No changes detected. Match score is already up to date.',
            'data' => [
                'matchId' => $match->match_id,
                'status' => $match->status
            ]
        ]);
    }

    // Save
    $match->team_one_score = $teamOne;
    $match->team_two_score = $teamTwo;
    $match->service_team = $request->get('serviceTeam');
    $match->service_player = $request->get('servicePlayer');
    $match->match_end_reason = $request->get('matchEndReason');
    $match->winner_team_id = $request->winnerTeamId;
    $match->status = 'Completed';
    $match->save();

    return response()->json([
        'code' => 200,
        'message' => 'Score updated successfully',
        'data' => [
            'matchId' => $match->match_id,
            'status' => $match->status
        ]
    ]);
}
public function getMatchStatus(Request $request)
{
    $user_id = $request->input('userId');  // from body or query
    $token = $request->header('token');

    // Auth check
    $authcheck = $this->authCheck($token, $user_id);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    // Validate required param matchId
    $matchId = $request->input('matchId');
    if (!$matchId) {
        return response()->json([
            'code' => 422,
            'message' => 'matchId is required.',
            'result' => null
        ]);
    }

    // Fetch match
    $match = DB::tabel('matches')->where('match_id', $matchId)
        ->when($request->input('tournamentId'), function ($q) use ($request) {
            $q->where('tournament_id', $request->input('tournamentId'));
        })
        ->first();

    if (!$match) {
        return response()->json([
            'code' => 404,
            'message' => 'Match not found.',
            'result' => null
        ]);
    }

    $teamOne = Team::find($match->team_a_id);
    $teamTwo = Team::find($match->team_b_id);

    return response()->json([
        'code' => 200,
        'message' => 'Match status fetched successfully',
        'data' => [
            'matchId' => $match->match_id,
            'tournamentId' => $match->tournament_id,
            'status' => $match->status,
            'teamOne' => [
                'teamId' => $teamOne->id ?? null,
                'teamName' => $teamOne->teamName ?? null,
                'score' => $match->team_one_score
            ],
            'teamTwo' => [
                'teamId' => $teamTwo->id ?? null,
                'teamName' => $teamTwo->teamName ?? null,
                'score' => $match->team_two_score
            ],
            'matchTimeUTC' => $match->match_start_date . 'T' . $match->match_start_time . 'Z',
            'location' => $match->location
        ]
    ]);
}

public function getTournamentsByType(Request $request)
{
    // Auth parameters from POST body and headers
    $user_id = $request->input('userId');  // userId from POST body
    $token = $request->header('token');    // token from header

    // Auth check
    $authcheck = $this->authCheck($token, $user_id);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    // Get filter parameters from POST body
    $type = $request->input('type');           // 'ongoing', 'completed', 'upcoming'
    $limit = $request->input('limit', 10);     // default 10
    $page = $request->input('page', 1);        // default 1

    $query = Tournament::query();
    // dd($query);

    if ($type === 'ongoing') {
        $query->where('start_date', '<=', now())
              ->where('end_date', '>=', now());
    } elseif ($type === 'completed') {
        $query->where('end_date', '<', now());
    } elseif ($type === 'upcoming') {
        $query->where('start_date', '>', now());
    }

    $totalItems = $query->count();
    $tournaments = $query->skip(($page - 1) * $limit)
                         ->take($limit)
                         ->get();

    return response()->json([
        'code' => 200,
        'message' => 'Tournaments fetched successfully',
            // 'currentPage' => (int)$page,
            // 'limit' => (int)$limit,
            'totalPages' => ceil($totalItems / $limit),
            'totalItems' => $totalItems,
        
        'data' => $tournaments
    ]);
}
public function getTournamentTeams(Request $request)
{
    $user_id = $request->input('userId');
    $token = $request->header('token');

    // Auth check
    $authcheck = $this->authCheck($token, $user_id);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    $tournamentId = $request->input('tournamentId');

    $teams = DB::table('teams')
        ->join('tournament_teams', 'teams.id', '=', 'tournament_teams.team_id')
        ->where('tournament_teams.tournament_id', $tournamentId)
        ->select('teams.*')
        ->get();

    return response()->json([
        'code' => 200,
        'message' => 'Teams fetched successfully',
        'data' => $teams
    ]);
}
public function addTournamentTeams(Request $request)
{
    $request->validate([
        'userId' => 'required|integer|exists:users,id',
        'tournamentId' => 'required',
        'teamIds' => 'required|array|min:1',
        'teamIds.*' => 'required|integer|exists:teams,id',
    ]);

    $userId = $request->input('userId');
    $token = $request->header('token');

    // Auth check
    $authcheck = $this->authCheck($token, $userId);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    $tournamentId = $request->input('tournamentId');
    $teamIds = $request->input('teamIds');

    // Get existing team IDs in DB
    $existingTeamIds = DB::table('tournament_teams')
        ->where('tournament_id', $tournamentId)
        ->pluck('team_id')
        ->toArray();

    // Find team IDs to add and remove
    $teamIdsToAdd = array_diff($teamIds, $existingTeamIds);
    $teamIdsToRemove = array_diff($existingTeamIds, $teamIds);

    // Prepare and insert new team links
    $insertData = [];
    foreach ($teamIdsToAdd as $teamId) {
        $insertData[] = [
            'tournament_id' => $tournamentId,
            'team_id' => $teamId,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    if (!empty($insertData)) {
        DB::table('tournament_teams')->insert($insertData);
    }

    // Remove old teams that are not in new input
    if (!empty($teamIdsToRemove)) {
        DB::table('tournament_teams')
            ->where('tournament_id', $tournamentId)
            ->whereIn('team_id', $teamIdsToRemove)
            ->delete();
    }

    return response()->json([
        'code' => 200,
        'message' => 'Tournament teams updated successfully.',
        'data'=>'',
        // 'added' => array_values($teamIdsToAdd),
        // 'removed' => array_values($teamIdsToRemove),
    ]);
}


public function addTournamentPlayers(Request $request)
{
    $request->validate([
        'userId' => 'required|integer|exists:users,id',
        'tournamentId' => 'required|string',
        // 'teamId' => 'required|integer|exists:teams,id',
        'playerIds' => 'required|array|min:1',
        'playerIds.*' => 'required',
    ]);
    // dd('dssfdf');

    $userId = $request->input('userId');
    $token = $request->header('token');

    // Auth check
    $authcheck = $this->authCheck($token, $userId);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    $tournamentId = $request->input('tournamentId');
    $teamId = $request->input('teamId');
    $playerIds = $request->input('playerIds');

    // Get existing player IDs already added
    $existingPlayerIds = DB::table('tournament_players')
        ->where('tournament_id', $tournamentId)
        // ->where('team_id', $teamId)
        ->pluck('player_id')
        ->toArray();

    // Calculate new and removed players
    $playerIdsToAdd = array_diff($playerIds, $existingPlayerIds);
    $playerIdsToRemove = array_diff($existingPlayerIds, $playerIds);

    // Insert new players
    $insertData = [];
    foreach ($playerIdsToAdd as $playerId) {
        $insertData[] = [
            'tournament_id' => $tournamentId,
            // 'team_id' => $teamId,
            'player_id' => $playerId,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    if (!empty($insertData)) {
        DB::table('tournament_players')->insert($insertData);
    }

    // Remove players not included in the new list
    if (!empty($playerIdsToRemove)) {
        DB::table('tournament_players')
            ->where('tournament_id', $tournamentId)
            // ->where('team_id', $teamId)
            ->whereIn('player_id', $playerIdsToRemove)
            ->delete();
    }

    return response()->json([
        'code' => 200,
        'message' => 'Tournament players updated successfully.',
        'data' => '',
        // 'added' => array_values($playerIdsToAdd),
        // 'removed' => array_values($playerIdsToRemove),
    ]);
}

public function getTournamentPlayers(Request $request)
{
    $request->validate([
        'userId' => 'required|integer|exists:users,id',
        'tournamentId' => 'required|string',
    ]);

    $userId = $request->input('userId');
    $token = $request->header('token');

    // Auth check
    $authcheck = $this->authCheck($token, $userId);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    $tournamentId = $request->input('tournamentId');

    $players = DB::table('tournament_players')
        ->join('users', 'tournament_players.player_id', '=', 'users.id')
        ->where('tournament_players.tournament_id', $tournamentId)
        ->select(
            'users.id as user_id',
            'users.first_name',
            'users.email',
            'tournament_players.team_id'
        )
        ->get()
        ->map(function ($player) {
            foreach ($player as $key => $value) {
                $player->$key = $value === null ? '' : $value;
            }
            return $player;
        });

    return response()->json([
        'code' => 200,
        'message' => 'Tournament players fetched successfully.',
        'data' => $players
    ]);
}


public function uploadImage(Request $request)
{
    // Validate file and type inputs
    $request->validate([
        'file' => 'required', // Max 5MB
        'type' => 'required',
    ]);

    // Get file and type
    $file = $request->file('file');
    $type = $request->input('type');

    // Failsafe: Check if file is valid
    if (!$file || !$file->isValid()) {
        return response()->json([
            'code' => 400,
            'message' => 'Invalid or missing file upload.',
        ], 400);
    }

    // Set folder path based on type
    switch ($type) {
        case 'register':
            $folder = 'uploads/registers';
            break;
        case 'tournament_logo':
            $folder = 'uploads/tournaments/logos';
            break;
        case 'tournament_banner':
            $folder = 'uploads/tournaments/banners';
            break;
        case 'team_logo':
            $folder = 'uploads/teams/logos';
            break;
        default:
            $folder = 'uploads/others';
            break;
    }

    // Create directory if it doesn't exist
    if (!file_exists(public_path($folder))) {
        mkdir(public_path($folder), 0755, true);
    }

    // Generate unique file name
    $filename = $type . '_' . time() . '.' . $file->getClientOriginalExtension();

    // Move the file to the designated folder
    $file->move(public_path($folder), $filename);

    // Generate public URL
    // $url = url($folder . '/' . $filename);
$url = str_replace('/public/index.php', '', url($folder . '/' . $filename));

    // Return success response
    return response()->json([
        'code' => 200,
        'message' => 'Image uploaded successfully',
        'data' => [
            'imageUrl' => $url
        ]
    ]);
}




}
