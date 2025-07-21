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

    $user = User::where('mobile', $request->phone)->first();
    $isNewUser = false;

    $cachedOtp = Cache::get('otp_' . $request->phone);

    // If OTP is invalid
    if ($cachedOtp != $request->otp) {
        // Check if user exists to return data
        if ($user) {
            return response()->json([
                'code' => 400,
                'message' => 'Invalid OTP',
                'data' => [
                    'isNewUser' => false,
                    'userId' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'image' => $user->image,
                    'path' => $user->path,
                    'token' => $user->device_token,
                    'is_active' => $user->is_active,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'mobile_verified' => $user->mobile_verified,
                    'email_verified' => $user->email_verified,
                    'gender' => $user->gender,
                    'location' => $user->location,
                    'dob' => $user->dob,
                ]
            ]);
        }

        // If no user exists
        return response()->json([
            'code' => 400,
            'message' => 'Invalid OTP',
            'data' => [
                'isNewUser' => true
            ]
        ]);
    }

    // OTP is valid
    if (!$user) {
        $user = User::create([
            'mobile' => $request->phone,
            'is_active' => 1,
            'mobile_verified' => 1,
        ]);
        $isNewUser = true;
    }

    $accessToken = Str::random(64);

    UserAuthToken::updateOrCreate(
        ['user_id' => $user->id],
        ['token' => $accessToken]
    );

    $response = [
        'code' => 200,
        'message' => 'OTP verified successfully',
        'data' => [
            'isNewUser' => $isNewUser,
            'token' => $accessToken,
        ]
    ];

    // Only include user details if it's NOT a new user
    if (!$isNewUser) {
        $response['data'] += [
            'userId' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'image' => $user->image,
            'path' => $user->path,
            'token' => $user->device_token,
            'is_active' => $user->is_active,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'mobile_verified' => $user->mobile_verified,
            'email_verified' => $user->email_verified,
            'gender' => $user->gender,
            'location' => $user->location,
            'dob' => $user->dob,
        ];
    }

    return response()->json($response);
}



//     public function verifyOtp(Request $request)
// {
//     $request->validate([
//         'phone' => 'required|string',
//         'otp' => 'required|string',
//     ]);

//     $cachedOtp = Cache::get('otp_' . $request->phone);

//     if ($cachedOtp != $request->otp) {
//         return response()->json(['code' => 400, 'message' => 'Invalid OTP', 'data' => (object)[]]);
//     }

//     $user = User::where('mobile', $request->phone)->first();
//     $isNewUser = false;

//     if (!$user) {
//         $user = User::create([
//             'mobile' => $request->phone,
//             'is_active' => 1,
//             'mobile_verified' => 1,
//         ]);
//         $isNewUser = true;
//     }

//     $accessToken = Str::random(64);

//     // Store or update token
//     UserAuthToken::updateOrCreate(
//         ['user_id' => $user->id],
//         ['token' => $accessToken]
//     );

//     return response()->json([
//         'code' => 200,
//         'message' => 'OTP verified successfully',
//         'data' => [
//             'userId'=>$user->id,
//             'token' => $accessToken,
//             'isNewUser' => $isNewUser
//         ]
//     ]);
// }


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

    // Auth check
    $authcheck = $this->authCheck($token, $user_id);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    $perPage = 10;
    $page = $request->input('page', 1);
    $search = $request->input('search'); // team name search
    $isUserTeamList = filter_var($request->input('isUserTeamList'), FILTER_VALIDATE_BOOLEAN); // cast to boolean

    // Base query
    $teamQuery = DB::table('teams')->select('id', 'teamName', 'teamLogo', 'adminId');

    // Search by team name
    if (!empty($search)) {
        $teamQuery->where('teamName', 'like', '%' . $search . '%');
    }

    // Filter only user teams if isUserTeamList is true
    if ($isUserTeamList) {
        $teamQuery->where(function ($q) use ($user_id) {
            $q->where('adminId', $user_id)
              ->orWhereIn('id', function ($sub) use ($user_id) {
                  $sub->select('team_id')
                      ->from('team_players')
                      ->where('userId', $user_id);
              });
        });
    }

    // Count total
    $totalRecords = $teamQuery->count();

    // Paginate
    $teams = $teamQuery->offset(($page - 1) * $perPage)
        ->limit($perPage)
        ->get();

    $teamIds = $teams->pluck('id')->toArray();

    // Get players
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

    $playersGrouped = $players->groupBy('team_id');

    // Format teams with players
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
    $user_id = $request->input('userId');
    $token = $request->header('token');

    // Auth check
    $authcheck = $this->authCheck($token, $user_id);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    $type = $request->input('type');           // 'ongoing', 'completed', 'upcoming'
    $limit = $request->input('limit', 10);
    $page = $request->input('page', 1);

    $query = Tournament::query();

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
                         ->get()
                         ->map(function ($item) {
                             return collect($item)->mapWithKeys(function ($value, $key) {
                                 return [$key => (string) $value];
                             });
                         });

    return response()->json([
        'code' => 200,
        'message' => 'Tournaments fetched successfully',
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
    $limit = $request->input('limit', 10);
    $page = $request->input('page', 1);

    // Step 1: Base query for total count
    $baseQuery = DB::table('teams')
        ->join('tournament_teams', 'teams.id', '=', 'tournament_teams.team_id')
        ->where('tournament_teams.tournament_id', $tournamentId);

    $totalItems = $baseQuery->count();
    $totalPages = ceil($totalItems / $limit);

    // Step 2: Get paginated teams
    $teams = $baseQuery->select('teams.*')
        ->skip(($page - 1) * $limit)
        ->take($limit)
        ->get();

    // Step 3: Attach players and convert to string
    foreach ($teams as $team) {
        $playerIds = DB::table('tournament_players')
            ->where('tournament_id', $tournamentId)
            ->where('team_id', $team->id) // Optional: filter by team if applicable
            ->pluck('player_id');

        $players = DB::table('users')
            ->whereIn('id', $playerIds)
            ->select('id', 'first_name', 'email', 'image')
            ->get();

        // Convert player values to string
        $team->players = $players->map(function ($player) {
            return collect($player)->mapWithKeys(function ($val, $key) {
                return [$key => (string) $val];
            });
        });

        // Convert team values to string (except 'players')
        foreach ($team as $key => $value) {
            if ($key !== 'players') {
                $team->$key = (string) $value;
            }
        }
    }

    return response()->json([
        'code' => 200,
        'message' => 'Teams with players fetched successfully',
        'totalPages' => $totalPages,
        'totalItems' => $totalItems,
        
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

    // 🟡 Count total teams after update
    $totalTeams = DB::table('tournament_teams')
        ->where('tournament_id', $tournamentId)
        ->count();

    // 🟢 Update tournaments table
    DB::table('tournaments')
        ->where('tournament_id', $tournamentId)
        ->update(['no_of_teams' => $totalTeams]);

    return response()->json([
        'code' => 200,
        'message' => 'Tournament teams updated successfully.',
        'data' => [
            'no_of_teams' => (string) $totalTeams // send as string if needed
        ]
    ]);
}


public function addTournamentPlayers(Request $request)
{
  $request->validate([
        'userId' => 'required|integer|exists:users,id',
        'tournamentId' => 'required|string',
        'playerIds' => 'required|array|min:1',
        'playerIds.*' => 'required',
    ]);

    $userId = $request->input('userId');
    $token = $request->header('token');

    // Auth check
    $authcheck = $this->authCheck($token, $userId);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    $tournamentId = $request->input('tournamentId');
    $playerIds = $request->input('playerIds');

    // Get existing player IDs
    $existingPlayerIds = DB::table('tournament_players')
        ->where('tournament_id', $tournamentId)
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
            ->whereIn('player_id', $playerIdsToRemove)
            ->delete();
    }

    // ✅ Count total players after update
    $totalPlayers = DB::table('tournament_players')
        ->where('tournament_id', $tournamentId)
        ->count();

    // ✅ Update the tournament's no_of_player field
    DB::table('tournaments')
        ->where('tournament_id', $tournamentId)
        ->update([
            'no_of_player' => $totalPlayers
        ]);

    return response()->json([
        'code' => 200,
        'message' => 'Tournament players updated successfully.',
        'data' => [
            'no_of_player' => (string) $totalPlayers
        ]
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
public function createMatchCustome(Request $request)
{
    try {
        // Extract token and userId from request
        $userId = $request->input('userId');
        $token = $request->header('token');

        // Auth check
        $authcheck = $this->authCheck($token, $userId);
        if (!empty($authcheck)) {
            return response()->json($authcheck, 401);
        }

        // If auth passes, get user model
        $user = User::find($userId); // replace with actual user fetching logic if needed

        // Validation
        $request->validate([
            'matchDetails' => 'required|array',
            'matchDetails.matchStartDate' => 'required|date',
            'matchDetails.matchStartTime' => 'required|date_format:H:i:s',
            'matchDetails.matchType' => 'required|string',
            'matchDetails.matchPointFormat' => 'required|string',
            'matchDetails.matchLocation' => 'required|string',
            'Team' => 'required|array|size:2',
            'Player' => 'required|array|min:2'
        ]);

        $matchDetails = $request->matchDetails;
        $teams = $request->Team;
        $players = $request->Player;

        $match_id = 'match_' . Str::random(8);

        // Insert match
        DB::table('matches')->insert([
            'match_id' => $match_id,
            'match_type' => $matchDetails['matchType'],
            'match_point_format' => $matchDetails['matchPointFormat'],
            'match_location' => $matchDetails['matchLocation'],
            'match_start_date' => $matchDetails['matchStartDate'],
            'match_start_time' => $matchDetails['matchStartTime'],
            'status' => 'Pending',
            'organizer_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert teams
        foreach ([0, 1] as $i) {
            DB::table('match_teams')->insert([
                'match_id' => $match_id,
                'team_name' => $teams[$i],
                'team_number' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert players
        foreach ($players as $index => $userId) {
            DB::table('match_players')->insert([
                'match_id' => $match_id,
                'team_number' => $index < 2 ? 1 : 2,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Match created successfully',
            'data' => ['matchId' => $match_id]
        ]);

    } catch (ValidationException $ve) {
        return response()->json([
            'code' => 422,
            'message' => 'Validation failed',
            'data' => $ve->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'code' => 500,
            'message' => 'Something went wrong',
            'data' => $e->getMessage()
        ], 500);
    }
}


public function getCustomMatchCustome(Request $request)
{
    try {
        // Extract token and userId
        $userId = $request->input('userId');
        $token = $request->header('token');

        // Auth check
        $authcheck = $this->authCheck($token, $userId);
        if (!empty($authcheck)) {
            return response()->json($authcheck, 401);
        }

        // Validate input
        $request->validate([
            'type' => 'nullable|string|in:completed,pending,cancelled',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $type = $request->input('type', 'completed');
        $page = $request->input('page');
        $limit = $request->input('limit', 10);

        // Fetch matches with filters
        $query = DB::table('matches')
            ->where('status', $type)
            ->orderBy('match_start_date', 'desc');

        $total = $query->count();
        $matches = $query->offset(($page - 1) * $limit)->limit($limit)->get();

        $result = [];

        foreach ($matches as $match) {
            $playerCount = DB::table('match_players')
                ->where('match_id', $match->match_id)
                ->count();

            $organizer = DB::table('users')
                ->where('id', $match->organizer_id)
                ->first();

            $result[] = [
                'matchId' => $match->match_id,
                'matchTitle' => ucfirst($match->match_type) . ' Match',
                'matchType' => strtolower($match->match_type),
                'startDate' => Carbon::parse($match->match_start_date . ' ' . $match->match_start_time)->toIso8601String(),
                'endDate' => Carbon::parse($match->match_start_date . ' ' . $match->match_start_time)->addHours(2)->toIso8601String(),
                'location' => $match->match_location,
                'status' => $match->status,
                'numberOfPlayers' => $playerCount,
                'organizer' => [
                    'name' => $organizer->name ?? 'Unknown',
                    'phone' => $organizer->phone ?? '',
                    'profileLogo' => $organizer->profile_image ?? '',
                ],
            ];
        }

        return response()->json([
            'code' => 200,
            'message' => 'Custom matches fetched successfully',
            // 'pagination' => [
                // 'currentPage' => (int) $page,
                // 'limit' => (int) $limit,
                'totalPages' => ceil($total / $limit),
                'totalItems' => $total,
            // ],
            'data' => $result
        ]);

    } catch (\Illuminate\Validation\ValidationException $ve) {
        return response()->json([
            'code' => 422,
            'message' => 'Validation error',
            'errors' => $ve->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'code' => 500,
            'message' => 'Something went wrong',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function getMatchCustomeDetails(Request $request)
{
    try {
        // Extract token and userId
        $userId = $request->input('userId');
        $token = $request->header('token');

        // Auth check
        $authcheck = $this->authCheck($token, $userId);
        if (!empty($authcheck)) {
            return response()->json($authcheck, 401);
        }

        // Match ID from query
        $matchId = $request->query('matchId');

        // Fetch match
        $match = DB::table('matches')->where('match_id', $matchId)->first();
        if (!$match) {
            return response()->json([
                'code' => 404,
                'message' => 'Match not found',
            ], 404);
        }

        // Basic match details
        $matchDetails = [
            'matchStartDate' => $match->match_start_date,
            'matchStartTime' => $match->match_start_time,
            'matchType' => $match->match_type,
            'matchPointFormat' => $match->match_point_format,
            'matchLocation' => $match->match_location,
        ];

        // Team 1 players
        $team1Players = DB::table('match_players')
            ->join('users', 'match_players.user_id', '=', 'users.id')
            ->where('match_players.match_id', $matchId)
            ->where('team_number', 1)
            ->select('users.id as userId', 'users.name', 'users.phone as phoneNumber', 'users.profile_image as profileLogo')
            ->get();

        // Team 2 players
        $team2Players = DB::table('match_players')
            ->join('users', 'match_players.user_id', '=', 'users.id')
            ->where('match_players.match_id', $matchId)
            ->where('team_number', 2)
            ->select('users.id as userId', 'users.name', 'users.phone as phoneNumber', 'users.profile_image as profileLogo')
            ->get();

        return response()->json([
            'code' => 200,
            'message' => 'Match details fetched successfully',
            'result' => [
                'matchId' => $match->match_id,
                'matchDetails' => $matchDetails,
                'team1' => $team1Players,
                'team2' => $team2Players,
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'code' => 500,
            'message' => 'Something went wrong',
            'error' => $e->getMessage(),
        ], 500);
    }
}
public function updateMatchCustomeScore(Request $request)
{
    try {
        // Step 1: Auth check
        $userId = $request->input('userId');
        $token = $request->header('token');

        $authcheck = $this->authCheck($token, $userId);
        if (!empty($authcheck)) {
            return response()->json($authcheck, 401);
        }

        // Step 2: Validate input
        $request->validate([
            'matchId' => 'required|string',
            'team1Score' => 'required|integer',
            'team2Score' => 'required|integer',
            'playerScores' => 'required|array',
            'playerScores.team1' => 'required|array',
            'playerScores.team2' => 'required|array',
        ]);

        $matchId = $request->matchId;

        // Step 3: Update team scores and status
        DB::table('matches')->where('match_id', $matchId)->update([
            'team1_score' => $request->team1Score,
            'team2_score' => $request->team2Score,
            'status' => 'completed',
            'updated_at' => now()
        ]);

        // Step 4: Update player scores for team 1
        foreach ($request->playerScores['team1'] as $player) {
            DB::table('match_players')
                ->where('match_id', $matchId)
                ->where('user_id', $player['userId'])
                ->update(['score' => $player['score']]);
        }

        // Step 5: Update player scores for team 2
        foreach ($request->playerScores['team2'] as $player) {
            DB::table('match_players')
                ->where('match_id', $matchId)
                ->where('user_id', $player['userId'])
                ->update(['score' => $player['score']]);
        }

        // Step 6: Return response
        return response()->json([
            'code' => 200,
            'message' => 'Match score updated successfully',
            'result' => (object)[]
        ]);
    } catch (\Illuminate\Validation\ValidationException $ve) {
        return response()->json([
            'code' => 422,
            'message' => 'Validation failed',
            'errors' => $ve->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'code' => 500,
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function details(Request $request)
{
    $userId = $request->input('userId');
    $token = $request->header('token');

    $authcheck = $this->authCheck($token, $userId);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    $user = User::find($userId);
    if (!$user) {
        return response()->json(['code' => 404, 'message' => 'User not found'], 404);
    }

    return response()->json([
        'code' => 200,
        'message' => 'Profile details fetched successfully',
        'data' => [
            'userId' => (string)$user->id,
            'fullName' => trim($user->first_name . ' ' . $user->last_name),
            'email' => $user->email,
            'mobileNumber' => $user->mobile,
            'image' => $user->image,
            'path' => $user->path,
            'gender' => $user->gender,
            'dob' => $user->dob,
            'location' => $user->location,
            'isActive' => (bool) $user->is_active,
            'mobileVerified' => (bool) $user->mobile_verified,
            'emailVerified' => (bool) $user->email_verified,
            'deviceToken' => $user->device_token,
             'pushNotifications' => (bool) $user->push_notifications,
            'mailNotifications' => (bool) $user->mail_notifications,
            'appSound' => (bool) $user->app_sound,
            'appVibrations' => (bool) $user->app_vibrations,

        ]
    ]);
}
public function edit(Request $request)
{
    $userId = $request->input('userId');
    $token = $request->header('token');

    $authcheck = $this->authCheck($token, $userId);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    $user = User::find($userId);
    if (!$user) {
        return response()->json(['code' => 404, 'message' => 'User not found'], 404);
    }

    // Validate fields
    $request->validate([
        'first_name' => 'nullable|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'mobile' => 'nullable|numeric',
        'gender' => 'nullable|string',
        'dob' => 'nullable|date',
        'location' => 'nullable|string|max:255',
        'image_url' => 'nullable|url',

        // Notification fields (boolean)
        'push_notifications' => 'nullable|boolean',
        'mail_notifications' => 'nullable|boolean',
        'app_sound' => 'nullable|boolean',
        'app_vibrations' => 'nullable|boolean',
    ]);

    // Update basic fields
    $user->first_name = $request->input('first_name', $user->first_name);
    $user->last_name = $request->input('last_name', $user->last_name);
    $user->mobile = $request->input('mobile', $user->mobile);
    $user->gender = $request->input('gender', $user->gender);
    $user->dob = $request->input('dob', $user->dob);
    $user->location = $request->input('location', $user->location);
    $user->image = $request->input('image_url', $user->image);

    // Update notification preferences
    $user->push_notifications = $request->input('push_notifications', $user->push_notifications);
    $user->mail_notifications = $request->input('mail_notifications', $user->mail_notifications);
    $user->app_sound = $request->input('app_sound', $user->app_sound);
    $user->app_vibrations = $request->input('app_vibrations', $user->app_vibrations);

    $user->save();

    return response()->json([
        'code' => 200,
        'message' => 'Profile updated successfully',
        'data' => [
            'userId' => (string) $user->id,
            'fullName' => $user->first_name . ' ' . $user->last_name,
            'image' => $user->image,
            'mobile' => $user->mobile,
            'gender' => $user->gender,
            'dob' => $user->dob,
            'location' => $user->location,

            // Return updated notification preferences
            'pushNotifications' => (bool) $user->push_notifications,
            'mailNotifications' => (bool) $user->mail_notifications,
            'appSound' => (bool) $user->app_sound,
            'appVibrations' => (bool) $user->app_vibrations,
        ]
    ]);
}



   public function logout(Request $request)
{
    $userId = $request->input('userId');
    $token = $request->header('token');

    // Step 1: Validate input
    if (empty($userId) || empty($token)) {
        return response()->json([
            'code' => 400,
            'status' => 'error',
            'message' => 'Missing userId or token',
        ]);
    }

    // Step 2: Check authentication
    $authcheck = $this->authCheck($token, $userId);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    // Step 3: Set token to null instead of delete
    $updated = UserAuthToken::where('user_id', $userId)
                            ->where('token', $token)
                            ->update(['token' => null]);

    if ($updated) {
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'You have logged out successfully.',
        ]);
    } else {
        return response()->json([
            'code' => 404,
            'status' => 'error',
            'message' => 'No matching token found to logout.',
        ]);
    }
}

public function deleteAccount(Request $request)
{
    $userId = $request->input('userId');
    $token = $request->header('token');

    // Step 1: Check token + userId validity
    $authCheck = $this->authCheck($token, $userId);
    if (!empty($authCheck)) {
        return response()->json($authCheck, 401);
    }

    // Step 2: Set is_active to 0 (soft delete)
    $user = User::find($userId);

    if (!$user) {
        return response()->json([
            'code' => 404,
            'status' => 'error',
            'message' => 'User not found.'
        ]);
    }

    $user->is_active = 0;
    $user->save();

    return response()->json([
        'code' => 200,
        'status' => 'success',
        'message' => 'Account deactivated successfully.'
    ]);
}

public function getNotifications(Request $request)
{
    $userId = $request->input('userId');
    $token = $request->header('token');

    // ✅ Step 1: Validate token and userId
    $authCheck = $this->authCheck($token, $userId);
    if (!empty($authCheck)) {
        return response()->json($authCheck, 401);
    }

    // ✅ Step 2: Fetch notifications
    $notifications = Notifications::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhereNull('user_id');
                //   ->orWhere('target_audience', '0'); // All users
        })
        // ->where('status', '1') // Only sent
        ->orderBy('created_at', 'desc')
        ->get();

    // ✅ Step 3: Return response
    return response()->json([
        'code' => 200,
        'message' => 'Notifications fetched successfully',
        'data' => $notifications
    ]);
}

public function myStats(Request $request)
{
    $userId = $request->input('userId');
    $token = $request->header('token');

    // Optional: Auth check
    $authcheck = $this->authCheck($token, $userId);
    if (!empty($authcheck)) {
        return response()->json($authcheck, 401);
    }

    // All values as strings
    $stats = [
        'total_matches_played' => '45',
        'total_tournaments' => '9',
        'matches_won' => '36',
        'matches_lost' => '9',
        'win_percentage' => '63.90%',
        'single_win_rate' => '90.09%',
        'double_win_rate' => '72.81%',
        'most_frequent_partner' => 'John M.',
        'tournaments_played' => '9',
        'tournaments_won' => '9',
    ];

    return response()->json([
        'code' => 200,
        'message' => 'Stats fetched successfully',
        'data' => $stats
    ]);
}

}
