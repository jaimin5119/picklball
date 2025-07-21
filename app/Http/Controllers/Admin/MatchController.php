<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Match;
use DataTables;
use DB;
class MatchController extends Controller
{
    public function index()
    {
        return view('admin.match.index');
    }

    public function getMatchesList(Request $request)
    {
        if ($request->ajax()) {
            $data = Match::select('id', 'match_id');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-danger btn-sm delete-match" data-id="'.$row->id.'">
                                <i class="fa fa-trash"></i> Delete
                            </button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function destroy($id)
    {
        Match::findOrFail($id)->delete();
        return response()->json(['success' => 'Match deleted successfully.']);
    }
}

