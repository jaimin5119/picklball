<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\User;
use DataTables;
use DB;

class TournamentController extends Controller
{
    // Show the tournaments list via AJAX
public function getTournamentsList(Request $request)
{
    if ($request->ajax()) {

        $data = DB::table('tournaments')
    ->leftJoin('users', 'tournaments.user_id', '=', 'users.id')
    ->select(
        'tournaments.id',
        'tournaments.tournament_name',
        'tournaments.match_type',
        DB::raw("CONCAT_WS(' ', users.first_name, users.last_name) as user_name")
    );


        // Debug actual data
        // dd($data->get());

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-danger btn-sm delete-tournament" data-id="'.$row->id.'">
                            <i class="fa fa-trash"></i>
                        </button>';
            })
            ->filterColumn('user_name', function($query, $keyword) {
    $query->whereRaw("CONCAT_WS(' ', users.first_name, users.last_name) like ?", ["%{$keyword}%"]);
})

            ->rawColumns(['action'])
            ->make(true);
    }
}



    // Delete a tournament
    public function delete(Request $request)
    {
        $tournament = Tournament::find($request->id);
        if ($tournament) {
            $tournament->delete();
            return response()->json(['success' => true, 'message' => 'Tournament deleted successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Tournament not found.']);
        }
    }
}

