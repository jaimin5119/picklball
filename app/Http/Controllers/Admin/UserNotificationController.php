<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\ScheduledNotification;
use App\Models\User;

class UserNotificationController extends Controller
{
    public function index()
{
    return view('admin.notificationlisting.index');
}
public function getScheduledNotifications(Request $request)
{
    if ($request->ajax()) {
        $data = ScheduledNotification::select([
            'id', 'title', 'message', 'target_audience', 'schedule_date', 'schedule_time', 'status'
        ]);

        return datatables()->of($data)
            ->addIndexColumn()

            // Format target audience
            ->editColumn('target_audience', function($row) {
                return $row->target_audience == 0 ? 'All Users' :
                       ($row->target_audience == 1 ? 'Active Users' : 'Other');
            })

            // Format status with badge
            ->editColumn('status', function($row) {
                return $row->status == 1 
                    ? '<span class="badge badge-success">Sent</span>' 
                    : '<span class="badge badge-warning">Pending</span>';
            })

            // Action button only for Pending
            ->addColumn('action', function($row) {
    $editBtn = '';
    $deleteBtn = '';

    if ($row->status == 0) { // Pending
        $editBtn = '<a href="'.route('admin.schedulededit', $row->id).'" class="btn btn-sm btn-info" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>';

        $deleteBtn = '<button class="btn btn-sm btn-danger delete-notification" data-id="'.$row->id.'" title="Delete">
                        <i class="fa fa-trash"></i>
                      </button>';
    }

    return $editBtn . ' ' . $deleteBtn;
})


            ->rawColumns(['status', 'action']) // ⚠ Add 'status' here for HTML badge rendering
            ->make(true);
    }
}



    // Show Add Notification Form
    public function create(Request $request)
    {
        return view('admin.notificationlisting.add');
    }

    // Store Notification (Send Now or Schedule Later)
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'message' => 'required|string',
        'target_audience' => 'required|in:0,1',
    ]);

    if ($request->submit_type === 'send_now') {
        $users = $request->target_audience == 0
            ? User::all()
            : User::where('is_active', '1')->get();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'message' => $request->message,
                'target_audience' => $request->target_audience,
                'status' => '1',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification sent successfully!'
        ]);
    }

    if ($request->submit_type === 'schedule') {
        ScheduledNotification::create([
            'title' => $request->title,
            'message' => $request->message,
            'schedule_date' => $request->schedule_date,
            'schedule_time' => $request->schedule_time,
            'status' => '0',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification scheduled successfully!'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Invalid submit type.'
    ]);
}
public function edit($id)
{
    $notification = ScheduledNotification::findOrFail($id);
    return view('admin.notificationlisting.edit', compact('notification'));
}

public function update(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'message' => 'required|string',
        'target_audience' => 'required|in:0,1',
        'schedule_date' => 'required|date',
        'schedule_time' => 'required'
    ]);

    $notification = ScheduledNotification::findOrFail($request->id);

    $notification->update([
        'title' => $request->title,
        'message' => $request->message,
        'target_audience' => $request->target_audience,
        'schedule_date' => $request->schedule_date,
        'schedule_time' => $request->schedule_time
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Notification updated successfully!'
    ]);
}


public function destroy(Request $request)
{
    $notification = ScheduledNotification::find($request->id);

    if (!$notification || $notification->status == 1) {
        return response()->json([
            'success' => false,
            'message' => 'Cannot delete this notification.'
        ]);
    }

    $notification->delete();

    return response()->json([
        'success' => true,
        'message' => 'Notification deleted successfully.'
    ]);
}


}