<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notifications;
use App\Models\UserNotification;


class NotificationController extends Controller
{
    public function index(){
        $list = Notifications::join('user_notifications as un', 'un.notif_id', '=', 'notifications.id')
                ->join('users as u', 'u.id', '=', 'un.user_id')
                ->select('u.first_name', 'u.last_name', 'u.mobile', 'notifications.title', 'notifications.message', 'notifications.created_at')
                ->get();

        return view('admin.notifications.list_notifications', ['list' => $list ]);
    }


    public function newNotif(){
        $users = User::whereNotNull('device_token')->where('is_active', 0)
                ->select('id', 'first_name', 'last_name')
                ->get();

        return view('admin.notifications.new_notif', ['users' => $users ]);
    }

    public function sendNotifi(Request $request){
        // return $request;

        $msg = $request->message;
        $usr = $request->userId;
        // send notification to the $request->userIds
        $SERVER_API_KEY = 'AAAAzmyW_1I:APA91bFt4rzkK03dd224eZxQ3YEIPdCG6UUKjzwMuodX0FT52ojH_awUmCn6-ZOqV16okmSEEgNFPSgWqFAGiWDScNHa5J2PzJFUYbgMxkQx-Tvm_0MojPkvQdx1mYWGRXJW3QSvfClL';

        $firebaseToken = User::whereNotNull('device_token')->where('is_active', 0)->whereIn('id', $usr)->pluck('device_token');
          
        // return $firebaseToken;
  
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->message,  
            ]
        ];

        $dataString = json_encode($data);
    
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
               
        $response = curl_exec($ch);
  
        // dd($response);

        $notif = Notifications::create([
            'title' => $request->title,
            'message' => $request->message,
        ]);

        if($notif->id){
            foreach ($usr as $val) {
                $un = UserNotification::create([
                    'user_id' => $val,
                    'notif_id' => $notif->id,
                ]);
            }
        }
        // else{
        //     return back()->withErrors('Error in sending notification.');
        // }

        return back()->with(['success' => 'Notification sent.']);
    }
}
