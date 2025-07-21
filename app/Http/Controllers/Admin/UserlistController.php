<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserlistController extends Controller
{

    public function index()
{
    return view('admin.userlisting.index');
}

public function getUsersList(Request $request)
{
    if ($request->ajax()) {
        
        $data = User::select(['id', 'first_name', 'last_name', 'email', 'mobile', 'gender', 'location', 'image', 'is_active']);

        return DataTables::of($data)
            ->addIndexColumn()

            // Combine first and last name
            ->addColumn('name', function ($row) {
                return $row->first_name . ' ' . $row->last_name;
            })

            ->filterColumn('name', function($query, $keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('first_name', 'like', "%{$keyword}%")
                      ->orWhere('last_name', 'like', "%{$keyword}%");
                });
            })

     ->addColumn('image', function ($row) {
    $imagePath = $row->image ? 'uploads/' . $row->id . '/' . $row->image : 'no-image.png';
    return asset($imagePath); // return full URL as string
})



            ->addColumn('action', function ($row) {
                $statusBtn = $row->is_active == '1'
                    ? '<button class="btn btn-success btn-sm toggle-status" data-id="'.$row->id.'" data-status="1">Active</button>'
                    : '<button class="btn btn-danger btn-sm toggle-status" data-id="'.$row->id.'" data-status="0">Inactive</button>';

                return $statusBtn;
            })

            ->rawColumns(['action', 'image']) // ✅ Important!
            ->make(true);
    }
}



public function toggleStatus(Request $request)
{
    $user = User::find($request->id);
    if ($user) {
        $user->is_active = $request->status;  // Use is_active column
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully.'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'User not found.'
    ]);
}


}
